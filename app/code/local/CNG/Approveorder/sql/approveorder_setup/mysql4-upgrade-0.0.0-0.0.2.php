<?php
$installer = $this;
$installer->startSetup();
 
$newFields = array(
    'approve_key' => array(
        'type' => 'text',
		'required' => 0,
		'input' => 'text',
		'default' => '',
    ),
    'delivery_time' => array(
        'type'  => 'text',
		'required' => 0,
		'input' => 'text',
		'default' => '',        
    )
);
    
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->addAttribute('order', 'approve_key', array(
            'default' => '',
            'type' => 'text',
            'input' => 'text',
            'visible' => true,
            'required' => false,
            'position' => 1
        ));
 $setup->addAttribute('order', 'manager_comment', array(
            'default' => '',
            'type' => 'text',
            'input' => 'text',
            'visible' => true,
            'required' => false,
            'position' => 1
        ));
$setup->addAttribute('order', 'approved_by', array(
            'default' => '',
            'type' => 'text',
            'input' => 'text',
            'visible' => true,
            'required' => false,
            'position' => 1
        ));
$installer->endSetup();
?>