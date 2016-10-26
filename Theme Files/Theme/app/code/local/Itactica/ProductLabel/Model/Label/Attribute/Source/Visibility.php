<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Model_Label_Attribute_Source_Visibility extends Mage_Eav_Model_Entity_Attribute_Source_Table
{
    /**
     * get possible values
     * @access public
     * @param bool $withEmpty
     * @param bool $defaultValues
     * @return array
     */
    public function getAllOptions($withEmpty = true, $defaultValues = false){
        $options =  array(
            array(
                'label' => Mage::helper('itactica_featuredproducts')->__('Catalog Page'),
                'value' => '1'
            ),
            array(
                'label' => Mage::helper('itactica_featuredproducts')->__('Product Page'),
                'value' => '2'
            ),
            array(
                'label' => Mage::helper('itactica_featuredproducts')->__('Catalog Page, Product Page'),
                'value' => '3'
            ),
        );
        if ($withEmpty) {
            array_unshift($options, array('label'=>'', 'value'=>''));
        }
        return $options;

    }
    /**
     * get options as array
     * @access public
     * @param bool $withEmpty
     * @return string
     */
    public function getOptionsArray($withEmpty = true) {
        $options = array();
        foreach ($this->getAllOptions($withEmpty) as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }
    /**
     * get option text
     * @access public
     * @param mixed $value
     * @return string
     */
    public function getOptionText($value) {
        $options = $this->getOptionsArray();
        if (!is_array($value)) {
            $value = array($value);
        }
        $texts = array();
        foreach ($value as $v) {
            if (isset($options[$v])) {
                $texts[] = $options[$v];
            }
        }
        return implode(', ', $texts);
    }
}
