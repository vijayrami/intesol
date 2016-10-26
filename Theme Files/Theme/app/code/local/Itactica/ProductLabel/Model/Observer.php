<?php
/**
 * Intenso Premium Theme
 *
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Model_Observer extends Mage_Core_Model_Abstract
{
    /**
     * Apply all rules for labels
     * Handle catalog_product_save_after event
     *
     * @return  Itactica_ProductLabel_Model_Observer
     */
    public function applyRules()
    {
        $collection = Mage::getModel('itactica_productlabel/label')->getCollection()
            ->addFieldToFilter('status', 1);
        foreach ($collection as $label) {
            $label->getResource()->applyAllRules($label);
        }
    }
}