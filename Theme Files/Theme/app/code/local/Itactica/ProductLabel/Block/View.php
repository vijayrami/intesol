<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Block_View extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * Set cache data
     */
    protected function _construct()
    {
        $this->addData(array('cache_lifetime' => null));
        $this->addCacheTag(array(
            Mage_Core_Model_Store::CACHE_TAG,
            Mage_Cms_Model_Block::CACHE_TAG
        ));
    }

    /**
     * Prepare labels
     * @access public
     * @return Itactica_ProductLabel_Block_Label_View
     */
    public function getLabels()
    {
        return Mage::getModel('itactica_productlabel/label')->getLabels($this->getProduct());
    }

}