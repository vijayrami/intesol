<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Model_Resource_Label_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * constructor
     * @access public
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('itactica_productlabel/label');
    }

    /**
     * Add date filter to collection
     * @access public
     * @return Itactica_ProductLabel_Model_Resource_Label_Collection
     */
    public function addDateFilter()
    {
        $date = Mage::app()->getLocale()->date();

        $dateFrom   = array();
        $dateFrom[] = array('date' => true, 'to' => date($date->toString('YYYY-MM-dd')));
        $dateFrom[] = array('date' => true, 'null' => true);

        $dateTo     = array();
        $dateTo[]   = array('date' => true, 'from' => date($date->toString('YYYY-MM-dd')));
        $dateTo[]   = array('date' => true, 'null' => true);

        $this->addFieldToFilter('status', 1);
        $this->addFieldToFilter('from_date', $dateFrom);
        $this->addFieldToFilter('to_date', $dateTo);

        return $this;
    }

}
