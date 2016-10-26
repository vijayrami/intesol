<?php
/**
 * Intenso Premium Theme
 *
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (http://www.itactica.com)
 * @license     http://getintenso.com/license
 */

class Itactica_ProductLabel_Model_Resource_Product_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * constructor
     * @access public
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('itactica_productlabel/product');
    }
}