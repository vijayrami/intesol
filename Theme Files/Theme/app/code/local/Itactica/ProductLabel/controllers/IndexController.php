<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * default action
     * @return void
     */
    public function indexAction() {
        $image = '';
        $html = '';
        $position = array();
        $response = array();
        $productIds = $this->getRequest()->getParam('productIds');

        if (is_array($productIds)) {
            $productIds = array_unique($productIds);
        }

        $labels = Mage::getModel('itactica_productlabel/label')->getLabels($productIds);
        $imagePath = Mage::helper('itactica_productlabel/image')->getImageBaseUrl();

        foreach ($productIds as $productId) {
            foreach ($labels[$productId] as $collection) {
                if (isset($position[$productId][$collection->getPosition()])) {
                    continue;
                } else {
                    $position[$productId][$collection->getPosition()] = true;
                }

                if ($collection->getImage()) {
                    $image = ' background-image: url(' . $imagePath . $collection->getImage() . ');';
                }

                $html .= '<div class="intenso-product-label-wrapper position-' . $collection->getPosition() . ' ' . $collection->getCustomClassname() . '" style="' . $collection->getLabelStyles() . '">';
                $html .= '<span class="intenso-product-label" style="' . $image . $collection->getTextStyles() . '">' . $collection->getText() . '</span>';
                $html .= '</div>';
            }
            if ($html !== '') $response[$productId] = $html;
            $html = '';
        }

        $this->getResponse()->setHeader('Content-Type', 'application/json', true);
        $this->getResponse()->setBody(json_encode($response));
    }
}
