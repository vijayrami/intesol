<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Block_Adminhtml_Label_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     * @access public
     */
    public function __construct() {
        parent::__construct();
        $this->setId('label_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('itactica_productlabel')->__('Product Labels'));
    }
    /**
     * before render html
     * @access protected
     * @return Itactica_ProductLabel_Block_Adminhtml_Label_Edit_Tabs
     */
    protected function _beforeToHtml(){
        $this->addTab('form_label', array(
            'label'     => Mage::helper('itactica_productlabel')->__('General'),
            'title'     => Mage::helper('itactica_productlabel')->__('General'),
            'content'   => $this->getLayout()->createBlock('itactica_productlabel/adminhtml_label_edit_tab_form')->toHtml(),
        ));
        $this->addTab('form_conditions', array(
            'label'     => Mage::helper('itactica_productlabel')->__('Conditions'),
            'title'     => Mage::helper('itactica_productlabel')->__('Conditions'),
            'content'   => $this->getLayout()->createBlock('itactica_productlabel/adminhtml_label_edit_tab_conditions')->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
    /**
     * Retrieve productlabel entity
     * @access public
     * @return Itactica_ProductLabel_Model_ProductLabel
     */
    public function getLabel(){
        return Mage::registry('current_productlabel');
    }
}
