<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Block_Adminhtml_Label_Helper_Image extends Varien_Data_Form_Element_Image
{
    /**
     * get the url of the image
     * @access protected
     * @return string
     */
    protected function _getUrl(){
        $url = false;
        if ($this->getValue()) {
            $url = Mage::helper('itactica_productlabel/image')->getImageBaseUrl().$this->getValue();
        }
        return $url;
    }
}
