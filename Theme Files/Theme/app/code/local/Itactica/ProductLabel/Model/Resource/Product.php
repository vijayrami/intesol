<?php
/**
 * Intenso Premium Theme
 *
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (http://www.itactica.com)
 * @license     http://getintenso.com/license
 */

class Itactica_ProductLabel_Model_Resource_Product extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * initialize resource model
     * @access protected
     * @see Mage_Core_Model_Resource_Abstract::_construct()
     */
    protected function _construct()
    {
        $this->_init('itactica_productlabel/rule_product', 'rule_product_id');
    }
}