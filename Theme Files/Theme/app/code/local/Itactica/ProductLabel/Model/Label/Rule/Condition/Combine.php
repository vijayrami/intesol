<?php
/**
 * Intenso Premium Theme
 *
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Model_Label_Rule_Condition_Combine extends Mage_Rule_Model_Condition_Combine
{
    /**
     * Groups definition
     *
     * @var array
     */
    protected $_groups = array(
        'base' => array(
            'name',
            'attribute_set_id',
            'sku',
            'category_ids',
            'url_key',
            'visibility',
            'status',
            'default_category_id',
            'meta_description',
            'meta_keyword',
            'meta_title',
            'price',
            'special_price',
            'special_price_from_date',
            'special_price_to_date',
            'tax_class_id',
            'short_description',
            'full_description'
        ),
        'extra' => array(
            'created_at',
            'updated_at',
            'news_from_date',
            'qty',
            'price_diff',
            'percent_discount'
        )
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setType('itactica_productlabel/label_rule_condition_combine');
    }

    /**
     * Generate a conditions data
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $productCondition  = Mage::getModel('itactica_productlabel/label_rule_condition_product');
        $productAttributes = $productCondition->loadAttributeOptions()->getAttributeOption();

        $attributes        = array();
        foreach ($productAttributes as $code => $label) {
            $group = 'attributes';
            foreach ($this->_groups as $key => $values) {
                if (in_array($code, $values)) {
                    $group = $key;
                }
            }
            $attributes[$group][] = array(
                'value' => 'itactica_productlabel/label_rule_condition_product|'.$code,
                'label' => $label
            );
        }

        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive($conditions, array(
            array(
                'value' => 'itactica_productlabel/label_rule_condition_combine',
                'label' => Mage::helper('itactica_productlabel')->__('Conditions Combination')
            ),
            array(
                'label' => Mage::helper('itactica_productlabel')->__('Product'),
                'value' => $attributes['base']
            ),
            array(
                'label' => Mage::helper('itactica_productlabel')->__('Product Additional'),
                'value' => $attributes['extra']
            ),
        ));

        if (isset($attributes['attributes'])) {
            $conditions = array_merge_recursive($conditions, array(
                array(
                    'label' => Mage::helper('itactica_productlabel')->__('Product Attribute'),
                    'value' => $attributes['attributes']
                ),
            ));
        }

        return $conditions;
    }

    /**
     * Collect all validated attributes
     * @param $productCollection
     *
     * @return $this
     */
    public function collectValidatedAttributes($productCollection)
    {
        foreach ($this->getConditions() as $condition) {
            $condition->collectValidatedAttributes($productCollection);
        }
        return $this;
    }
}
