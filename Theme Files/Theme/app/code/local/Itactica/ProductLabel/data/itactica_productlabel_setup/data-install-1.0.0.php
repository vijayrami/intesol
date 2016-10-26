<?php
/**
 * Intenso Premium Theme
 *
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

/** @var $installer Itactica_ProductLabel_Model_Resource_Setup */
$installer = $this;

// create default labels
$now = Mage::getSingleton('core/date')->gmtDate();
$labels = array(
    array(
        'name'                  => 'New',
        'priority'              => 0,
        'position'              => 'top-right',
        'text'                  => 'NEW',
        'image'                 => '',
        'label_styles'          => 'margin: 6px;',
        'text_styles'           => 'color: #fff; background: #F22613; border-radius: 3px; padding: 3px 5px 4px; font-size: 10px;',
        'visibility'            => 3,
        'conditions_serialized' => 'a:7:{s:4:"type";s:50:"itactica_productlabel/label_rule_condition_combine";s:9:"attribute";N;s:8:"operator";N;s:5:"value";s:1:"1";s:18:"is_value_processed";N;s:10:"aggregator";s:3:"all";s:10:"conditions";a:1:{i:0;a:5:{s:4:"type";s:50:"itactica_productlabel/label_rule_condition_product";s:9:"attribute";s:14:"news_from_date";s:8:"operator";s:2:"<=";s:5:"value";s:2:"30";s:18:"is_value_processed";b:0;}}}',
        'store_ids'             => '1',
        'customer_group_ids'    => '0,1,2,3',
        'status'                => 1,
        'updated_at'            => $now,
        'created_at'            => $now,
    ),
    array(
        'name'                  => 'Discount',
        'priority'              => 0,
        'position'              => 'top-left',
        'text'                  => '{SAVE_PERCENT}%{BR}OFF',
        'image'                 => '/circle-buttercup.svg',
        'label_styles'          => 'margin: 6px;',
        'text_styles'           => 'color: #fff; padding: 10px; font-size: 16px; font-weight: lighter;',
        'visibility'            => 3,
        'conditions_serialized' => 'a:7:{s:4:"type";s:50:"itactica_productlabel/label_rule_condition_combine";s:9:"attribute";N;s:8:"operator";N;s:5:"value";s:1:"1";s:18:"is_value_processed";N;s:10:"aggregator";s:3:"all";s:10:"conditions";a:1:{i:0;a:5:{s:4:"type";s:50:"itactica_productlabel/label_rule_condition_product";s:9:"attribute";s:16:"percent_discount";s:8:"operator";s:2:">=";s:5:"value";s:2:"10";s:18:"is_value_processed";b:0;}}}',
        'store_ids'             => '1',
        'customer_group_ids'    => '0,1,2,3',
        'status'                => 1,
        'updated_at'            => $now,
        'created_at'            => $now,
    ),
);

foreach ($labels as $label) {
    Mage::getModel('itactica_productlabel/label')
        ->setData($label)
        ->save();
}