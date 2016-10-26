<?php
/**
 * Intenso Premium Theme
 *
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Model_Label_Rule_Condition_Product extends Mage_Rule_Model_Condition_Abstract
{
    /**
     * All attribute values as array in form:
     * array(
     *   [entity_id_1] => array(
     *          [store_id_1] => store_value_1,
     *          [store_id_2] => store_value_2,
     *          ...
     *          [store_id_n] => store_value_n
     *   ),
     *   ...
     * )
     *
     * Will be set only for not global scope attribute
     *
     * @var array
     */
    protected $_entityAttributeValues = null;

    /**
     * Attribute data key that indicates whether it should be used for rules
     *
     * @var string
     */
    protected $_isUsedForRuleProperty = 'is_used_for_promo_rules';

    /**
     * Retrieve attribute object
     *
     * @return Mage_Catalog_Model_Resource_Eav_Attribute
     */
    public function getAttributeObject()
    {
        try {
            $obj = Mage::getSingleton('eav/config')
                ->getAttribute('catalog_product', $this->getAttribute());
        }
        catch (Exception $e) {
            $obj = new Varien_Object();
            $obj->setEntity(Mage::getResourceSingleton('catalog/product'))
                ->setFrontendInput('text');
        }
        return $obj;
    }

    /**
     * Add special attributes
     *
     * @param array $attributes
     */
    protected function _addSpecialAttributes(array &$attributes)
    {
        $attributes['attribute_set_id'] = Mage::helper('itactica_productlabel')->__('Attribute Set');
        $attributes['category_ids']     = Mage::helper('itactica_productlabel')->__('Category');
        $attributes['created_at']       = Mage::helper('itactica_productlabel')->__('Created At (days ago)');
        $attributes['updated_at']       = Mage::helper('itactica_productlabel')->__('Updated At (days ago)');
        $attributes['news_from_date']   = Mage::helper('itactica_productlabel')->__('Set as New (days ago)');
        $attributes['qty']              = Mage::helper('itactica_productlabel')->__('Quantity');
        $attributes['price_diff']       = Mage::helper('itactica_productlabel')->__('Price - Final Price');
        $attributes['percent_discount'] = Mage::helper('itactica_productlabel')->__('Percent Discount');
    }

    /**
     * Load attribute options
     *
     * @return Itactica_ProductLabel_Model_Label_Rule_Condition_Product
     */
    public function loadAttributeOptions()
    {
        $productAttributes = Mage::getResourceSingleton('catalog/product')
            ->loadAllAttributes()
            ->getAttributesByCode();

        $attributes = array();
        foreach ($productAttributes as $attribute) {
            if (!$attribute->isAllowedForRuleCondition() || !$attribute->getDataUsingMethod($this->_isUsedForRuleProperty)) {
                continue;
            }
            $attributes[$attribute->getAttributeCode()] = $attribute->getFrontendLabel();
        }

        $this->_addSpecialAttributes($attributes);

        asort($attributes);
        $this->setAttributeOption($attributes);

        return $this;
    }

    /**
     * Prepares values options to be used as select options or hashed array
     * Result is stored in following keys:
     *  'value_select_options' - normal select array: array(array('value' => $value, 'label' => $label), ...)
     *  'value_option' - hashed array: array($value => $label, ...),
     *
     * @return Itactica_ProductLabel_Model_Label_Rule_Condition_Product
     */
    protected function _prepareValueOptions()
    {
        // Check that both keys exist. Maybe somehow only one was set not in this routine, but externally.
        $selectReady = $this->getData('value_select_options');
        $hashedReady = $this->getData('value_option');
        if ($selectReady && $hashedReady) {
            return $this;
        }

        // Get array of select options. It will be used as source for hashed options
        $selectOptions = null;
        if ($this->getAttribute() === 'attribute_set_id') {
            $entityTypeId = Mage::getSingleton('eav/config')
                ->getEntityType('catalog_product')->getId();
            $selectOptions = Mage::getResourceModel('eav/entity_attribute_set_collection')
                ->setEntityTypeFilter($entityTypeId)
                ->load()
                ->toOptionArray();
        } else if (is_object($this->getAttributeObject())) {
            $attributeObject = $this->getAttributeObject();
            if ($attributeObject->usesSource()) {
                if ($attributeObject->getFrontendInput() == 'multiselect') {
                    $addEmptyOption = false;
                } else {
                    $addEmptyOption = true;
                }
                $selectOptions = $attributeObject->getSource()->getAllOptions($addEmptyOption);
            }
        }

        // Set new values only if we really got them
        if ($selectOptions !== null) {
            // Overwrite only not already existing values
            if (!$selectReady) {
                $this->setData('value_select_options', $selectOptions);
            }
            if (!$hashedReady) {
                $hashedOptions = array();
                foreach ($selectOptions as $o) {
                    if (is_array($o['value'])) {
                        continue; // We cannot use array as index
                    }
                    $hashedOptions[$o['value']] = $o['label'];
                }
                $this->setData('value_option', $hashedOptions);
            }
        }

        return $this;
    }

    /**
     * Retrieve value by option
     *
     * @param mixed $option
     * @return string
     */
    public function getValueOption($option=null)
    {
        $this->_prepareValueOptions();
        return $this->getData('value_option'.(!is_null($option) ? '/'.$option : ''));
    }

    /**
     * Retrieve select option values
     *
     * @return array
     */
    public function getValueSelectOptions()
    {
        $this->_prepareValueOptions();
        return $this->getData('value_select_options');
    }

    /**
     * Retrieve after element HTML
     *
     * @return string
     */
    public function getValueAfterElementHtml()
    {
        $html = '';

        switch ($this->getAttribute()) {
            case 'sku': case 'category_ids':
            $image = Mage::getDesign()->getSkinUrl('images/rule_chooser_trigger.gif');
            break;
        }

        if (!empty($image)) {
            $html = '<a href="javascript:void(0)" class="rule-chooser-trigger"><img src="' . $image . '" alt="" class="v-middle rule-chooser-trigger" title="' . Mage::helper('rule')->__('Open Chooser') . '" /></a>';
        }
        return $html;
    }

    /**
     * Retrieve attribute element
     *
     * @return Varien_Form_Element_Abstract
     */
    public function getAttributeElement()
    {
        $element = parent::getAttributeElement();
        $element->setShowAsText(true);
        return $element;
    }

    /**
     * Collect all validated attributes
     *
     * @param $productCollection
     * @return $this
     */
    public function collectValidatedAttributes($productCollection)
    {
        $attribute = $this->getAttribute();

        if (!in_array($attribute, array('category_ids', 'qty', 'price_diff', 'percent_discount', 'news_from_date'))) {
            if ($this->getAttributeObject()->isScopeGlobal()) {
                $attributes = $this->getRule()->getCollectedAttributes();
                $attributes[$attribute] = true;
                $this->getRule()->setCollectedAttributes($attributes);
                $productCollection->addAttributeToSelect($attribute, 'left');
            } else {
                $this->_entityAttributeValues = $productCollection->getAllAttributeValues($attribute);
            }
        } elseif (($attribute == 'price_diff')
            || ($attribute == 'percent_discount')) {
            $productCollection->addAttributeToSelect('price', 'left');
            $productCollection->addAttributeToSelect('special_price', 'left');
            $productCollection->addAttributeToSelect('special_from_date', 'left');
            $productCollection->addAttributeToSelect('special_to_date', 'left');
            $productCollection->addAttributeToSelect('type_id', 'left');
        }

        return $this;
    }

    /**
     * Retrieve input type
     *
     * @return string
     */
    public function getInputType()
    {
        if ($this->getAttribute() === 'attribute_set_id') {
            return 'select';
        }
        if (!is_object($this->getAttributeObject())) {
            return 'string';
        }
        switch ($this->getAttributeObject()->getFrontendInput()) {
            case 'select':
                return 'select';

            case 'multiselect':
                return 'multiselect';

            case 'boolean':
                return 'boolean';

            default:
                return 'string';
        }
    }

    /**
     * Retrieve value element type
     *
     * @return string
     */
    public function getValueElementType()
    {
        if ($this->getAttribute() === 'attribute_set_id') {
            return 'select';
        }
        if (!is_object($this->getAttributeObject())) {
            return 'text';
        }
        switch ($this->getAttributeObject()->getFrontendInput()) {
            case 'select':
            case 'boolean':
                return 'select';

            case 'multiselect':
                return 'multiselect';

            default:
                return 'text';
        }
    }


    /**
     * Retrieve value element chooser URL
     *
     * @return string
     */
    public function getValueElementChooserUrl()
    {
        $url = false;
        switch ($this->getAttribute()) {
            case 'sku': case 'category_ids':
            $url = 'adminhtml/promo_widget/chooser'
                .'/attribute/'.$this->getAttribute();
            if ($this->getJsFormObject()) {
                $url .= '/form/'.$this->getJsFormObject();
            }
            break;
        }
        return $url!==false ? Mage::helper('adminhtml')->getUrl($url) : '';
    }

    /**
     * Retrieve Explicit Apply
     *
     * @return bool
     */
    public function getExplicitApply()
    {
        switch ($this->getAttribute()) {
            case 'sku': case 'category_ids':
            return true;
        }

        return false;
    }

    /**
     * Load array
     *
     * @param array $arr
     * @return Mage_CatalogRule_Model_Rule_Condition_Product
     */
    public function loadArray($arr)
    {
        $this->setAttribute(isset($arr['attribute']) ? $arr['attribute'] : false);
        $attribute = $this->getAttributeObject();

        if ($attribute && $attribute->getBackendType() == 'decimal') {
            if (isset($arr['value'])) {
                if (!empty($arr['operator'])
                    && in_array($arr['operator'], array('!()', '()'))
                    && false !== strpos($arr['value'], ',')) {

                    $tmp = array();
                    foreach (explode(',', $arr['value']) as $value) {
                        $tmp[] = Mage::app()->getLocale()->getNumber($value);
                    }
                    $arr['value'] =  implode(',', $tmp);
                } else {
                    $arr['value'] =  Mage::app()->getLocale()->getNumber($arr['value']);
                }
            } else {
                $arr['value'] = false;
            }
            $arr['is_value_parsed'] = isset($arr['is_value_parsed'])
                ? Mage::app()->getLocale()->getNumber($arr['is_value_parsed']) : false;
        }

        return parent::loadArray($arr);
    }

    /**
     * Validate product attribute value for condition
     *
     * @param Varien_Object $object
     * @return bool
     */
    public function validate(Varien_Object $object)
    {
        $attrCode       = $this->getAttribute();
        $defaultStoreId = Mage::app()
            ->getWebsite(true)
            ->getDefaultGroup()
            ->getDefaultStoreId();
        $object->setStoreId($defaultStoreId);

        if ('category_ids' == $attrCode) {
            $op = $this->getOperatorForValidate();
            if (($op == '==')
                ||($op == '!=')) {
                if (is_array($object->getAvailableInCategories())) {
                    $value = $this->getValueParsed();
                    $value = preg_split('#\s*[,;]\s*#', $value, null, PREG_SPLIT_NO_EMPTY);
                    $findElemInArray = array_intersect($object->getAvailableInCategories(), $value);
                    if (count($findElemInArray) > 0) {
                        if ($op == '==') {
                            $result = true;
                        }
                        if ($op == '!=') {
                            $result = false;
                        }
                    } else {
                        if ($op == '==') {
                            $result = false;
                        }
                        if ($op == '!=') {
                            $result = true;
                        }
                    }
                    return $result;
                }
            } else {
                return $this->validateAttribute($object->getAvailableInCategories());
            }
        } elseif ('created_at' == $attrCode) {
            $ago = (time() - strtotime($object->getCreatedAt())) / 60 / 60 / 24;
            return $this->validateAttribute($ago);
        } elseif ('news_from_date' == $attrCode) {
            $_product = Mage::getModel('catalog/product')->load($object->getId());
            if ($_product->getNewsFromDate()) {
                $ago = (time() - strtotime($_product->getNewsFromDate())) / 60 / 60 / 24;
            } else {
                $ago = null;
            }
            return $this->validateAttribute($ago);
        } elseif ('updated_at' == $attrCode) {
            $ago = (time() - strtotime($object->getUpdatedAt())) / 60 / 60 / 24;
            return $this->validateAttribute($ago);
        } elseif ('qty' == $attrCode) {
            $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($object->getId());
            if ($stockItem->getTypeId() == 'configurable') {
                if($stockItem->getIsInStock()) {
                    $requiredChildrenIds = Mage::getResourceSingleton('catalog/product_type_configurable')
                        ->getChildrenIds($object->getId(), true);
                    $childrenIds = array();
                    foreach ($requiredChildrenIds as $groupedChildrenIds) {
                        $childrenIds = array_merge($childrenIds, $groupedChildrenIds);
                    }
                    $sumQty = 0;
                    foreach ($childrenIds as $childId) {
                        $childQty = Mage::getModel('cataloginventory/stock_item')->loadByProduct($childId)->getQty();
                        $sumQty += $childQty;
                    }
                    return $this->validateAttribute($sumQty);
                } else {
                    return false;
                }
            } else {
                return $this->validateAttribute($stockItem->getQty());
            }
        } elseif ('price_diff' == $attrCode) {
            $price  = $object->getPrice();
            $final  = Mage::getModel('catalogrule/rule')->calcProductPriceRule($object, $object->getPrice());
            if (!$final) {
                $final  = $object->getSpecialPrice();
                if ($final) {
                    $specialPriceFromDate = $object->getSpecialFromDate();
                    $specialPriceToDate   = $object->getSpecialToDate();
                    $today =  time();
                    if (($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate))
                        || ($today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate))) {

                        return $this->validateAttribute(abs($price - $final));
                    } else {
                        return false;
                    }
                }
            }
            if ($final) {
                return $this->validateAttribute(abs($price - $final));
            }
        } elseif ('percent_discount' == $attrCode) {
            $prodPrice  = $object->getPrice();
            $storeId = $object->getStoreId();
            if ($storeId) {
                $final = Mage::getResourceModel('catalogrule/rule')->getRulePrice(
                    Mage::app()->getLocale()->storeTimeStamp($storeId),
                    Mage::app()->getStore($storeId)->getWebsiteId(),
                    Mage::getSingleton('customer/session')->getCustomerGroupId(), $object->getId())
                ;
                $inDateInterval = true;
            }
            if (!$final) {
                $final  = $object->getSpecialPrice();
                $specialPriceFromDate = $object->getSpecialFromDate();
                $specialPriceToDate   = $object->getSpecialToDate();
                $today =  time();
                $inDateInterval = false;
                if (($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate))
                    || ($today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate))) {

                    $inDateInterval = true;
                }
            }

            if (($final > 0)
                && $inDateInterval) {
                $percent = (($prodPrice-$final)/$prodPrice) * 100;
                return $this->validateAttribute(abs($percent));
            }
            return false;
        } elseif ('attribute_set_id' == $attrCode) {
            $attrId = $object->getAttributeSetId();

            return $this->validateAttribute($attrId);
        } elseif (! isset($this->_entityAttributeValues[$object->getId()])) {
            $attr = $object->getResource()->getAttribute($attrCode);

            if ($attr && $attr->getBackendType() == 'datetime' && !is_int($this->getValue())) {
                $this->setValue(strtotime($this->getValue()));

                $value = strtotime($object->getData($attrCode));
                return $this->validateAttribute($value);
            }

            if ($attr && $attr->getFrontendInput() == 'multiselect') {
                $value = $object->getData($attrCode);
                $value = strlen($value) ? explode(',', $value) : array();
                return $this->validateAttribute($value);
            }

            return parent::validate($object);
        } else {
            $result = false; // any valid value will set it to TRUE
            $oldAttrValue = $object->hasData($attrCode) ? $object->getData($attrCode) : null; // remember old attribute state
            foreach ($this->_entityAttributeValues[$object->getId()] as $storeId => $value) {
                $attr = $object->getResource()->getAttribute($attrCode);
                if ($attr && $attr->getBackendType() == 'datetime') {
                    $value = strtotime($value);
                } else if ($attr && $attr->getFrontendInput() == 'multiselect') {
                    $value = strlen($value) ? explode(',', $value) : array();
                }

                $object->setData($attrCode, $value);
                $result |= parent::validate($object);

                if ($result) {
                    break;
                }
            }

            if (is_null($oldAttrValue)) {
                $object->unsetData($attrCode);
            } else {
                $object->setData($attrCode, $oldAttrValue);
            }

            return (bool) $result;
        }
    }
}
