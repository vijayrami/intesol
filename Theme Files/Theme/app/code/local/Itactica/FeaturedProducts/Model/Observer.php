<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_FeaturedProducts
 * @copyright   Copyright (c) 2014-2016 Itactica (http://www.itactica.com)
 * @license     http://getintenso.com/license
 */

class Itactica_FeaturedProducts_Model_Observer
{
	/**
	 * Before load layout event handler
	 *
	 * @param Varien_Event_Observer $observer
	 */
    public function beforeLoadLayout($observer) {
		$observer->getEvent()->getLayout()->getUpdate()
			->addHandle('itactica_featured_products');
    }
}