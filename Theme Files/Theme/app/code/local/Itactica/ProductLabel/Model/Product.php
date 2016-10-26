<?php
/**
 * Intenso Premium Theme
 *
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Model_Product extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     * @access protected
     * @return void
     */
    protected function _construct(){
        $this->_init('itactica_productlabel/product');
    }
}