<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Block_Adminhtml_Label_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     * @access public
     */
    public function __construct(){
        parent::__construct();
        $this->_blockGroup = 'itactica_productlabel';
        $this->_controller = 'adminhtml_label';
        $this->_updateButton('save', 'label', Mage::helper('itactica_productlabel')->__('Save Label'));
        $this->_updateButton('delete', 'label', Mage::helper('itactica_productlabel')->__('Delete Label'));
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('itactica_productlabel')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     * @access public
     * @return string
     */
    public function getHeaderText(){
        if( Mage::registry('current_productlabel') && Mage::registry('current_productlabel')->getId() ) {
            return Mage::helper('itactica_productlabel')->__("Edit Label '%s'", $this->escapeHtml(Mage::registry('current_productlabel')->getName()));
        }
        else {
            return Mage::helper('itactica_productlabel')->__('Add Label');
        }
    }
}
