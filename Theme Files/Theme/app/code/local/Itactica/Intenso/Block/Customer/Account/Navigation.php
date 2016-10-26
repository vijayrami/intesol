<?php

/**
 * Intenso Premium Theme
 *
 * @category    Itactica
 * @package     Itactica_Intenso
 * @copyright   Copyright (c) 2014-2016 Itactica (http://www.itactica.com)
 * @license     http://getintenso.com/license
 */

class Itactica_Intenso_Block_Customer_Account_Navigation extends Mage_Customer_Block_Account_Navigation
{
    /**
     * @return $this
     */
    public function removeLink()
    {
        $storeId = Mage::app()->getStore();
        $items = Mage::getStoreConfig('intenso/customer_account/remove_customer_account_links', $storeId);
        $links = explode(',', $items);
        foreach ($links as $link) {
            unset($this->_links[$link]);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    protected function _toHtml()
    {
        if (Mage::getSingleton('core/design_package')->getPackageName() == 'intenso') {
            $this->removeLink();
        }
        return parent::_toHtml();
    }
}