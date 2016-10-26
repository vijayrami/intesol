<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_Intenso
 * @copyright   Copyright (c) 2014 Itactica (http://www.itactica.com)
 * @license     http://getintenso.com/license
 */

class Itactica_Intenso_Model_Observer
{
	/**
	 * generate CSS file after store edit
	 *
	 * @access public
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
    public function afterStoreEdit($observer) {
    	$store = $observer->getEvent()->getStore();
		$storeCode = $store->getCode();
		$websiteCode = $store->getWebsite()->getCode();
		
		Mage::getSingleton('itactica_intenso/css_generator')->generateCssFromConfig($storeCode, $websiteCode);
    }

    /**
	 * generate CSS file after theme config edit
	 *
	 * @access public
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
    public function afterConfigSave() {
    	$configSection = Mage::app()->getRequest()->getParam('section');

		if ($configSection == 'intenso' || $configSection == 'intenso_design') {
			$storeCode = Mage::app()->getRequest()->getParam('store');
			$websiteCode = Mage::app()->getRequest()->getParam('website');
			Mage::getSingleton('itactica_intenso/css_generator')->generateCssFromConfig($storeCode, $storeCode);
		}
    }

    /**
     * observe the post dispatch for adding to cart
     * removes add to cart success message
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function addToCartRedirect($observer) {
    	$disableAddToCartMsg = Mage::getStoreConfig('intenso/global_messages/addtocart_success_msg',
			Mage::app()->getStore());
    	if ($disableAddToCartMsg) {
			// check for qty error message in grouped products
			$productId = Mage::app()->getRequest()->getParam('product');
			if ($productId) {
				$product = Mage::getModel('catalog/product')->load($productId);
				$productType = $product->getTypeId();
				if ($productType == Mage_Catalog_Model_Product_Type_Grouped::TYPE_CODE
					&& max(Mage::app()->getRequest()->getParam('super_group')) == 0) {
					return;
				}
			}
    		// remove success notification message after adding product to cart
        	Mage::getSingleton("checkout/session")->getMessages(true);
    	}
    }
}