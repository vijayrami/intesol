<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_LogoSlider
 * @copyright   Copyright (c) 2014-2015 Itactica (http://www.itactica.com)
 * @license     http://getintenso.com/license
 */

$this->startSetup();

$this->getConnection()->addColumn(
    $this->getTable('itactica_logoslider/slider'),
    'black_and_white',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        'comment'   => 'Black and White'
    )
);

$this->endSetup();
