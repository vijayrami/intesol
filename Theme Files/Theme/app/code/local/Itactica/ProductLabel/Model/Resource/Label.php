<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Model_Resource_Label extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * constructor
     * @access public
     */
    public function _construct()
    {
        $this->_init('itactica_productlabel/label', 'label_id');
    }

    /**
     * Returns all label ids matching the product
     * @param int $productId
     * @access public
     */
    public function ruleLabelIds($productId)
    {
        $isRuleProductId = Mage::getModel('itactica_productlabel/product')->getCollection()
            ->addFieldToFilter('product_id', $productId);

        return $isRuleProductId->getColumnValues('label_id');
    }

    /**
     * Apply all catalog price rules
     * @param Itactica_ProductLabel_Model_Label $label
     * @access public
     * @return void
     * @throws Exception
     */
    public function applyAllRules($label)
    {
        $labelId     = $label->getId();
        $productIds = $label->getMatchingProductIds();
        $write      = $this->_getWriteAdapter();

        $write->beginTransaction();
        $write->delete($this->getTable('itactica_productlabel/rule_product'), $write->quoteInto('label_id = ?', $labelId));

        $rows = array();
        $queryStart = 'INSERT INTO '.$this->getTable('itactica_productlabel/rule_product').' (
                label_id, product_id) values ';
        $queryEnd = ' ON DUPLICATE KEY UPDATE product_id=VALUES(product_id)';

        try {
            foreach ($productIds as $productId) {
                $rows[] = "('".implode("','", array($labelId, $productId))."')";

                if (sizeof($rows) == 1000) {
                    $sql = $queryStart.join(',', $rows).$queryEnd;
                    $write->query($sql);
                    $rows = array();
                }
            }

            if (!empty($rows)) {
                $sql = $queryStart.join(',', $rows).$queryEnd;
                $write->query($sql);
            }

            $write->commit();
        } catch (Exception $e) {
            $write->rollback();
            throw $e;
        }

        return $this;
    }

}
