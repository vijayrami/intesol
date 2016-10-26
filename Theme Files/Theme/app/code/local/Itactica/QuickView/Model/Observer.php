<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_Intenso
 * @copyright   Copyright (c) 2014-2015 Itactica (http://www.itactica.com)
 * @license     http://getintenso.com/license
 */

class Itactica_QuickView_Model_Observer
{
	/**
	 * observe the post dispatch for adding to compare list
	 * and redirects to the correct referrer instead of the quickview page
	 *
	 * @access public
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
    public function addToCompareRedirect($observer) {
    	//get the real referrer from server var
        $referrer = Mage::app()->getRequest()->getServer('HTTP_REFERER');
        if ($referrer){
            //set new redirect
            Mage::app()->getResponse()->setRedirect($referrer);
        }
        return $this;
    }

    /**
     * observe the post dispatch for adding to cart
     * and redirects to the correct referrer instead of the quickview page
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function addToCartRedirect($observer) {
        //get the real referrer from server var
        $realReferrer = Mage::app()->getRequest()->getServer('HTTP_REFERER');
        $referrer = $realReferrer;
        try {
            // check if redirection was set in header
            $headers = Mage::app()->getResponse()->getHeaders();
            foreach ($headers as $key => $value) {
                if (isset($value['name']) && $value['name'] == 'Location') {
                    $referrer = $value['value'];
                    // exclude redirection from QuickView
                    if (strpos($referrer,'itactica_quickview') !== false) {
                        $referrer = $realReferrer;
                    }
                } else {
                    $referrer = $realReferrer;
                }
            }
        } catch (Mage_Core_Exception $e) {
            // no referrer in header, continue with real referer
            $referrer = $realReferrer;
        }

        if (Mage::helper('checkout/cart')->getShouldRedirectToCart()) {
            $productId = Mage::app()->getRequest()->getParam('product');
            if ($productId) {
                $product = Mage::getModel('catalog/product')->load($productId);
                $productType = $product->getTypeId();
                if ($productType == Mage_Catalog_Model_Product_Type_Grouped::TYPE_CODE
                    && max(Mage::app()->getRequest()->getParam('super_group')) == 0) {
                    Mage::app()->getResponse()->setRedirect($referrer);
                    return;
                }
            }

            $referrer = Mage::helper('checkout/cart')->getCartUrl();
            Mage::getSingleton('checkout/session')->setContinueShoppingUrl($realReferrer);
        }

        //redirect
        Mage::app()->getResponse()->setRedirect($referrer);

        return $this;
    }
}