<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Block_Adminhtml_Label_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare layout.
     * Add files to use dialog windows
     *
     * @return Mage_Adminhtml_Block_System_Email_Template_Edit_Form
     */
    protected function _prepareLayout()
    {
        if ($head = $this->getLayout()->getBlock('head')) {
            $head->addItem('js', 'prototype/window.js')
                ->addItem('js', 'mage/adminhtml/variables.js');
        }
        return parent::_prepareLayout();
    }

    /**
     * prepare the form
     * @access protected
     * @return Itactica_ProductLabel_Block_Adminhtml_Label_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('current_productlabel');

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('rule_');

        $fieldset = $form->addFieldset('base_fieldset',
            array('legend'=>Mage::helper('itactica_productlabel')->__('General Information'))
        );
        $fieldset->addType('image', Mage::getConfig()->getBlockClassName('itactica_productlabel/adminhtml_label_helper_image'));

        if ($model->getId()) {
            $fieldset->addField('label_id', 'hidden', array(
                'name' => 'label_id',
            ));
        }

        $isCronRunning = Mage::helper('itactica_productlabel')->isCronRunning();
        if ($isCronRunning !== true) {
            $fieldset->addField('cron_disabled', 'note', array(
                'name'  => 'cronjob_status',
                'label' => Mage::helper('itactica_productlabel')->__('Cron Job'),
                'note'  => '<span style="color:red;"><b>Cron is not running</b></span><br>Cron jobs, or scheduled tasks, must be enabled to allow the automatic update of the labels. Cron jobs are also required by Magento to operate properly. To setup a cron job please follow this <a target="_blank" href="http://support.getintenso.com/support/solutions">link</a>',
            ));
        }

        $fieldset->addField('name', 'text', array(
            'label' => Mage::helper('itactica_productlabel')->__('Name'),
            'name'  => 'name',
            'required'  => true,
            'class' => 'required-entry',
        ));

        $fieldset->addField('priority', 'text', array(
            'label'     => Mage::helper('itactica_productlabel')->__('Priority'),
            'name'      => 'priority',
            'note'      => Mage::helper('itactica_productlabel')->__('Integer between 0 (maximum priority) and 99 (very low priority)<br>If two or more labels share the same position, only the label with highest priority will be applied.'),
            'required'  => true,
            'class'     => 'validate-number validate-zero-or-greater validate-number-range number-range-0-99',
            'required'  => true,
        ));

        $fieldset->addField('custom_classname', 'text', array(
            'label' => Mage::helper('itactica_productlabel')->__('Custom Class Name'),
            'name'  => 'custom_classname',
            'note'  => Mage::helper('itactica_productlabel')->__('Add a custom class name if you wish to further customize the label style using CSS. Refer to this name on your custom.css file'),
            'class' => 'validate-code',
        ));

        $fieldset->addField('position', 'select', array(
            'label' => Mage::helper('itactica_productlabel')->__('Position'),
            'name'  => 'position',
            'values'=> Mage::getModel('itactica_productlabel/label_attribute_source_position')->getAllOptions(false),
        ));

        $fieldset->addField('text', 'text', array(
            'label' => Mage::helper('itactica_productlabel')->__('Text'),
            'name'  => 'text',
            'note'  => Mage::helper('itactica_productlabel')->__('Available variables:<br>{PRICE} = Price<br>{SPECIAL_PRICE} = Special price<br>{SAVE_PERCENT} = Discount percentage<br>{SAVE_AMOUNT} = Discount amount<br>{ATTR:code} = Attribute value<br>{SKU} = Product SKU<br>{BR} = New line'),
        ));


        $fieldset->addField('image', 'image', array(
            'label' => Mage::helper('itactica_productlabel')->__('Label Image'),
            'name'  => 'image',
            'note'  => Mage::helper('itactica_productlabel')->__('Recommended formats: SVG, PNG'),
        ));

        $fieldset->addField('label_styles', 'textarea', array(
            'label' => Mage::helper('itactica_productlabel')->__('Wrapper Styles'),
            'name'  => 'label_styles',
            'style' => 'height:8em;',
            'note'  => $this->__('CSS styles for the label container.<br>Example: padding: 4px; margin-top: 6px;'),
        ));

        $fieldset->addField('text_styles', 'textarea', array(
            'label' => Mage::helper('itactica_productlabel')->__('Label Styles'),
            'name'  => 'text_styles',
            'style' => 'height:8em;',
            'note'  => $this->__('CSS styles for the text.<br>Example: font-size: 1rem; color: #ff0000;'),
        ));

        $fieldset->addField('visibility', 'select', array(
            'label' => Mage::helper('itactica_productlabel')->__('Visibility'),
            'name'  => 'visibility',
            'values'=> Mage::getModel('itactica_productlabel/label_attribute_source_visibility')->getAllOptions(false),
        ));

        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $fieldset->addField('from_date', 'date', array(
            'name'   => 'from_date',
            'label'  => Mage::helper('catalogrule')->__('From Date'),
            'title'  => Mage::helper('catalogrule')->__('From Date'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso
        ));

        $fieldset->addField('to_date', 'date', array(
            'name'   => 'to_date',
            'label'  => Mage::helper('catalogrule')->__('To Date'),
            'title'  => Mage::helper('catalogrule')->__('To Date'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso
        ));

        $fieldset->addField('store_ids', 'multiselect', array(
            'label'     => Mage::helper('itactica_productlabel')->__('Visible In'),
            'required'  => true,
            'name'      => 'store_ids[]',
            'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(),
        ));

        $fieldset->addField('customer_group_ids', 'multiselect', array(
            'name'      => 'customer_group_ids[]',
            'label'     => Mage::helper('catalogrule')->__('Customer Groups'),
            'title'     => Mage::helper('catalogrule')->__('Customer Groups'),
            'required'  => true,
            'values'    => Mage::getResourceModel('customer/group_collection')->toOptionArray()
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('itactica_productlabel')->__('Status'),
            'name'  => 'status',
            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('itactica_productlabel')->__('Enabled'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('itactica_productlabel')->__('Disabled'),
                ),
            ),
        ));

        $formValues = Mage::registry('current_productlabel')->getDefaultValues();
        if (!is_array($formValues)){
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getProductLabelData()){
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getProductLabelData());
            Mage::getSingleton('adminhtml/session')->setProductLabelData(null);
        }
        elseif (Mage::registry('current_productlabel')){
            $formValues = array_merge($formValues, Mage::registry('current_productlabel')->getData());
        }
        $form->setValues($formValues);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
