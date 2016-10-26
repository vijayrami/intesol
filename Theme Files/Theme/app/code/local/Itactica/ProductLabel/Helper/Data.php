<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * convert array to options
     * @access public
     * @param $options
     * @return array
     */
    public function convertOptions($options){
        $converted = array();
        foreach ($options as $option){
            if (isset($option['value']) && !is_array($option['value']) && isset($option['label']) && !is_array($option['label'])){
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }

    /**
     * Check if cron is running
     * @return bool
     */
    public function isCronRunning()
    {
        $collection = Mage::getModel('cron/schedule')->getCollection()
            ->addFieldToFilter('status', 'success')
            ->setOrder('scheduled_at', 'desc')
            ->setPageSize(1);

        $job = $collection->getFirstItem();
        if (!$job->getId()) {
            return false;
        }

        $jobTimestamp = strtotime($job->getExecutedAt());
        $timestamp    = Mage::getSingleton('core/date')->gmtTimestamp();

        if (abs($timestamp - $jobTimestamp) > 6 * 60 * 60) {
            return false;
        }

        return true;
    }

}
