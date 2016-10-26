<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (http://www.itactica.com)
 * @license     http://getintenso.com/license
 */

class Itactica_ProductLabel_Block_Adminhtml_Label_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	/**
     * render row
     * @access public
     * @param Varien_Object $row
     * @return string
     */
	public function render(Varien_Object $row)
    {
    	if ($imageUrl = $row->getData($this->getColumn()->getIndex())) {
	        $html = '<img ';
	        $html .= 'id="' . $this->getColumn()->getId() . '" ';
	        $html .= 'src="' . Mage::getBaseUrl('media').'productlabel/image/' .$imageUrl . '"';
	        $html .= 'class="grid-image ' . $this->getColumn()->getInlineCss() . '" style="max-width: 80px; max-height: 60px;"/>';
	    } else {
	    	$html = '';
	    }
	        return $html;
    }
}