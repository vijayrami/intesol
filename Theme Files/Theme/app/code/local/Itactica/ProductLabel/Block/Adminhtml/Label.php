<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Block_Adminhtml_Label extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     * @access public
     */
    public function __construct(){
        $this->_controller         = 'adminhtml_label';
        $this->_blockGroup         = 'itactica_productlabel';
        parent::__construct();
        $this->_headerText         = Mage::helper('itactica_productlabel')->__('Product Labels');
        $this->_updateButton('add', 'label', Mage::helper('itactica_productlabel')->__('Add Label'));
    }
}
