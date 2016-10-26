<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Block_Adminhtml_Label_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     * @access public
     */
    public function __construct(){
        parent::__construct();
        $this->setId('labelGrid');
        $this->setDefaultSort('label_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
    /**
     * prepare collection
     * @access protected
     * @return Itactica_ProductLabel_Block_Adminhtml_Label_Grid
     */
    protected function _prepareCollection(){
        $collection = Mage::getModel('itactica_productlabel/label')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    /**
     * prepare grid collection
     * @access protected
     * @return Itactica_ProductLabel_Block_Adminhtml_Label_Grid
     */
    protected function _prepareColumns(){
        $this->addColumn('label_id', array(
            'header'    => Mage::helper('adminhtml')->__('Id'),
            'index'        => 'label_id',
            'type'        => 'number'
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('adminhtml')->__('Name'),
            'align'     => 'left',
            'index'     => 'name',
        ));
        $this->addColumn('status', array(
            'header'    => Mage::helper('adminhtml')->__('Status'),
            'index'        => 'status',
            'type'        => 'options',
            'options'    => array(
                '1' => Mage::helper('adminhtml')->__('Enabled'),
                '0' => Mage::helper('adminhtml')->__('Disabled'),
            )
        ));
        $this->addColumn('priority', array(
            'header'=> Mage::helper('itactica_productlabel')->__('Priority'),
            'index' => 'priority',
            'type'  => 'text',
            'width' => '40px',
            'align' => 'center'
        ));
        $this->addColumn('position', array(
            'header'=> Mage::helper('itactica_productlabel')->__('Position'),
            'index' => 'position',
            'type'  => 'options',
            'options' => Mage::helper('itactica_productlabel')->convertOptions(Mage::getModel('itactica_productlabel/label_attribute_source_position')->getAllOptions(false))
        ));

        $this->addColumn('image', array(
            'header'    => Mage::helper('catalog')->__('Image'),
            'align'     => 'left',
            'index'     => 'image',
            'renderer'  => 'itactica_productlabel/adminhtml_label_renderer_image',
            'align'     => 'center',
            'width'     => '40px'
        ));

        $this->addColumn('text', array(
            'header'=> Mage::helper('itactica_productlabel')->__('Text'),
            'index' => 'text',
            'type'  => 'text',
        ));
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn('store_ids', array(
                'header'=> Mage::helper('itactica_productlabel')->__('Store Views'),
                'index' => 'store_ids',
                'type'  => 'store',
                'store_all' => true,
                'store_view'=> true,
                'sortable'  => false,
                'filter_condition_callback'=> array($this, '_filterStoreCondition'),
            ));
        }
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('itactica_productlabel')->__('Created at'),
            'index'     => 'created_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('itactica_productlabel')->__('Updated at'),
            'index'     => 'updated_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        $this->addColumn('action',
            array(
                'header'=>  Mage::helper('itactica_productlabel')->__('Action'),
                'width' => '100',
                'type'  => 'action',
                'getter'=> 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('itactica_productlabel')->__('Edit'),
                        'url'   => array('base'=> '*/*/edit'),
                        'field' => 'id'
                    )
                ),
                'filter'=> false,
                'is_system'    => true,
                'sortable'  => false,
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('itactica_productlabel')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('itactica_productlabel')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('itactica_productlabel')->__('XML'));
        return parent::_prepareColumns();
    }
    /**
     * prepare mass action
     * @access protected
     * @return Itactica_ProductLabel_Block_Adminhtml_Label_Grid
     */
    protected function _prepareMassaction(){
        $this->setMassactionIdField('label_id');
        $this->getMassactionBlock()->setFormFieldName('label');
        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('itactica_productlabel')->__('Delete'),
            'url'  => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('itactica_productlabel')->__('Are you sure?')
        ));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('itactica_productlabel')->__('Change status'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'status' => array(
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('adminhtml')->__('Status'),
                        'values' => array(
                                '1' => Mage::helper('adminhtml')->__('Enabled'),
                                '0' => Mage::helper('adminhtml')->__('Disabled'),
                        )
                )
            )
        ));
        return $this;
    }
    /**
     * get the row url
     * @access public
     * @param Itactica_ProductLabel_Model_Label
     * @return string
     */
    public function getRowUrl($row){
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    /**
     * get the grid url
     * @access public
     * @return string
     */
    public function getGridUrl(){
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
    /**
     * after collection load
     * @access protected
     * @return Itactica_ProductLabel_Block_Adminhtml_Label_Grid
     */
    protected function _afterLoadCollection(){
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
    /**
     * filter store column
     * @access protected
     * @param Itactica_ProductLabel_Model_Resource_Label_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Itactica_ProductLabel_Block_Adminhtml_Label_Grid
     */
    protected function _filterStoreCondition($collection, $column){
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
