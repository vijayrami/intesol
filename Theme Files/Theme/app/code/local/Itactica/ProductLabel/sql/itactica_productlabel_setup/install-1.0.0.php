<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('itactica_productlabel/label'))
    ->addColumn('label_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Label ID')

    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Name')

    ->addColumn('priority', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    ), 'Priority')

    ->addColumn('custom_classname', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
        'nullable'  => false,
    ), 'Custom classname')

    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Position')

    ->addColumn('text', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Text')

    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Image filename')

    ->addColumn('label_styles', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Label CSS Styles')

    ->addColumn('text_styles', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Text CSS Styles')

    ->addColumn('visibility', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
    ), 'Visibility')

    ->addColumn('from_date', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
    ), 'From Date')

    ->addColumn('to_date', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
    ), 'From Date')

    ->addColumn('conditions_serialized', Varien_Db_Ddl_Table::TYPE_TEXT, '64K', array(
        'nullable'  => false,
    ), 'Conditions Serialized')

    ->addColumn('store_ids', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        'default'   => '0',
    ), 'Store ids')

    ->addColumn('customer_group_ids', Varien_Db_Ddl_Table::TYPE_TEXT, '64K', array(
        'nullable'  => false,
    ), 'Customer Group ids')

    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        ), 'Label status')

    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            ), 'Label modification time')

    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Label Creation Time') 

    ->setComment('Product Labels Table');
$this->getConnection()->createTable($table);

$table = $this->getConnection()
    ->newTable($this->getTable('itactica_productlabel/rule_product'))
    ->addColumn('rule_product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Rule product Id')

    ->addColumn('label_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'unsigned'  => true,
        'default'   => '0',
    ), 'Label Id')

    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'unsigned'  => true,
        'default'   => '0',
    ), 'Product Id')

    ->setComment('Rule Product Table');
$this->getConnection()->createTable($table);

$this->endSetup();
