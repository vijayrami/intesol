<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Model_Label extends Mage_Rule_Model_Abstract
{
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'itactica_productlabel_label';

    /**
     * Store rule combine conditions model
     *
     * @var Mage_Rule_Model_Condition_Combine
     */
    protected $_conditions;

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'label';
    protected $_productInstance = null;

    /**
     * Is model can be deleted flag
     *
     * @var bool
     */
    protected $_isDeleteable = true;

    /**
     * Is model readonly
     *
     * @var bool
     */
    protected $_isReadonly = false;

    /**
     * Product ids
     *
     * @var array
     */
    protected $_productIds;

    /**
     * constructor
     * @access public
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('itactica_productlabel/label');
        $this->setIdFieldName('label_id');
    }

    protected function _afterSave()
    {
        $this->_getResource()->applyAllRules($this);
        parent::_afterSave();
    }

    /**
     * Getter for rule conditions collection
     *
     * @return Mage_CatalogRule_Model_Rule_Condition_Combine
     */
    public function getConditionsInstance()
    {
        return Mage::getModel('itactica_productlabel/label_rule_condition_combine');
    }

    /**
     * Getter for rule actions collection
     *
     * @return Mage_CatalogRule_Model_Rule_Action_Collection
     */
    public function getActionsInstance()
    {
        return Mage::getModel('catalogrule/rule_action_collection');
    }

    /**
     * before save productlabel
     * @access protected
     * @return Itactica_ProductLabel_Model_Label
     */
    protected function _beforeSave(){
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()){
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);

        /**
         * Prepare website Ids if applicable and if they were set as string in comma separated format.
         * Backwards compatibility.
         */
        if ($this->hasStoreIds()) {
            $storeIds = $this->getStoreIds();
            if (is_array($storeIds)) {
                $this->setStoreIds(implode(',', $storeIds));
            }
        }

        /**
         * Prepare customer group Ids if applicable and if they were set as string in comma separated format.
         * Backwards compatibility.
         */
        if ($this->hasCustomerGroupIds()) {
            $groupIds = $this->getCustomerGroupIds();
            if (is_array($groupIds)) {
                $this->setCustomerGroupIds(implode(',', $groupIds));
            }
        }

        return $this;
    }

    /**
     * Initialize rule model data from array
     *
     * @param array $data
     * @return Mage_Rule_Model_Abstract
     */
    public function loadPost(array $data)
    {
        $arr = $this->_convertFlatToRecursive($data);
        if (isset($arr['conditions'])) {
            $this->getConditions()->setConditions(array())->loadArray($arr['conditions'][1]);
        }

        return $this;
    }

    /**
     * Get labels
     *
     * @param array $productIds
     * @return Itactica_ProductLabel_Model_Label
     */
    public function getLabels($productIds)
    {
        if (!empty($productIds)) {
            $values = array();
            $attributes = array();
            $labels = array();

            if (Mage::app()->getFrontController()->getRequest()->getControllerName() == 'category') {
                $visibility = array('1', 3);
            } else if (Mage::app()->getFrontController()->getRequest()->getControllerName() == 'product') {
                $visibility = array(2, 3);
            } else {
                $visibility = array(1, 2, 3);
            }

            $products = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('entity_id', array('in' => (array)$productIds))
                ->load();

            foreach ($products as $product) {
                foreach ($product->getAttributes() as $attribute) {
                    $attributes[$product->getId()][$attribute->getAttributeCode()] = $attribute->getFrontend()->getValue($product);
                }
            }

            foreach ($productIds as $productId) {
                $labelIds = $this->_getResource()->ruleLabelIds($productId);
                $labels[$productId] = Mage::getModel('itactica_productlabel/label')->getCollection()
                    ->addFieldToFilter('label_id', array('in' => $labelIds))
                    ->addDateFilter()
                    ->addFieldToFilter('store_ids', array('finset' => Mage::app()->getStore()->getId()))
                    ->addFieldToFilter('visibility', array('in' => $visibility))
                    ->addFieldToFilter('customer_group_ids', array('finset' => Mage::getSingleton('customer/session')->getCustomerGroupId()))
                    ->setOrder('priority', 'asc');


                foreach ($attributes[$productId] as $attributeCode => $attributeValue) {
                    $values['ATTR:' . $attributeCode] = $attributeValue;
                }

                $price = (isset($attributes[$productId]['price'])) ? $attributes[$productId]['price'] : '';
                $specialPrice = (isset($attributes[$productId]['special_price'])) ? $attributes[$productId]['special_price'] : '';
                $saveAmount = $price - $specialPrice;
                if ($price > 0) {
                    $savePercent = intval($price - $specialPrice) / $price * 100;
                    $values['SAVE_PERCENT'] = round($savePercent, 0);
                } else {
                    $values['SAVE_PERCENT'] = 0;
                }
                $values['PRICE'] = round($price, 2);
                $values['SPECIAL_PRICE'] = round($specialPrice, 2);
                $values['SAVE_AMOUNT'] = round($saveAmount, 2);
                $values['SKU'] = $attributes[$productId]['sku'];
                $values['BR'] = '<br>';

                foreach ($labels[$productId] as $label) {
                    $label->setText($this->parseVariables($label->getText(), $values));
                }
            }

            return $labels;
        }
    }

    /**
     * Replace variables from Text field with actual values
     *
     * @param string $text
     * @param array $values
     * @return Itactica_ProductLabel_Model_Label
     */
    protected function parseVariables($text, $values)
    {
        foreach($values as $key => $value) {
            if (!is_array($value)) {
                $text = str_replace('{' . $key . '}', $value, $text);
            }
        }

        return ($text) ? $text : '';
    }

    /**
     * Get array of product ids which are matched by rule
     *
     * @return array
     */
    public function getMatchingProductIds()
    {
        if (is_null($this->_productIds)) {
            $this->_productIds = array();
            $this->setCollectedAttributes(array());

            $productCollection = Mage::getResourceModel('catalog/product_collection');

            $this->getConditions()->collectValidatedAttributes($productCollection);

            Mage::getSingleton('core/resource_iterator')->walk(
                $productCollection->getSelect(),
                array(array($this, 'callbackValidateProduct')),
                array(
                    'attributes' => $this->getCollectedAttributes(),
                    'product'    => Mage::getModel('catalog/product'),
                )
            );
        }

        return $this->_productIds;
    }

    /**
     * Callback function for product matching
     *
     * @param $args
     * @return void
     */
    public function callbackValidateProduct($args)
    {
        $product = clone $args['product'];
        $product->setData($args['row']);

        if ($this->getConditions()->validate($product)) {
            $this->_productIds[] = $product->getId();
        }
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     */
    public function getDefaultValues() {
        $values = array();
        $values['priority'] = 0;
        $values['position'] = 'top-right';
        $values['visibility'] = 3;
        $values['status'] = 1;

        return $values;
    }
}
