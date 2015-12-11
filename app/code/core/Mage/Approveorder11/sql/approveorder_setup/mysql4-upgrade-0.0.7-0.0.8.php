<?php
$installer = $this;
$installer->startSetup();
 
$newFields = array(
    'function' => array(
        'type' => 'text',
		'required' => 0,
		'input' => 'text',
		'default' => '',
    ),
    'cost_center' => array(
        'type' => 'text',
		'required' => 0,
		'input' => 'text',
		'default' => '',
    ),
    'delivery_venue' => array(
        'type' => 'text',
		'required' => 0,
		'input' => 'text',
		'default' => '',
    )
);
    
$order = Mage::getModel('sales/order');
$entityType = $order->getResource()->getEntityType();
$entityTypeId = $entityType->getId();
    
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
foreach($newFields as $attributeName => $attributeDefs) {    
	$setup->addAttribute($entityTypeId, $attributeName,
		array(
		    'position'  => 1,
		    'type'		=> $attributeDefs['type'],
		    'required'	=> $attributeDefs['required'],
			'input'		=> $attributeDefs['input'],
			'default'	=> $attributeDefs['default'],
		)
	);
}
 
$installer->endSetup();
?>