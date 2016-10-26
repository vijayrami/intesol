<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Model_Adminhtml_Search_Label extends Varien_Object
{
    /**
     * Load search results
     * @access public
     * @return Itactica_ProductLabel_Model_Adminhtml_Search_Label
     */
    public function load(){
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('itactica_productlabel/label_collection')
            ->addFieldToFilter('name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $label) {
            $arr[] = array(
                'id'=> 'label/1/'.$label->getId(),
                'type'  => Mage::helper('itactica_productlabel')->__('Label'),
                'name'  => $label->getName(),
                'description'   => $label->getName(),
                'url' => Mage::helper('adminhtml')->getUrl('*/productlabel_label/edit', array('id'=>$label->getId())),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
