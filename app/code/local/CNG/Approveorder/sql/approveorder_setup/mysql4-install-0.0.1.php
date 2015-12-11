<?php
$installer = $this;
$installer->startSetup();
 
$installer->run("
CREATE TABLE {$this->getTable('kitchen')} (
  `kitchen_id` int(10) unsigned NOT NULL auto_increment,
  `order_id` int(10) unsigned,
  `order_status` varchar(10) NOT NULL default 'Pending',
  `item_id` int(10) unsigned,
  `item_name` varchar(50) NOT NULL default '',
  `item_qty` int(5) unsigned,
  `customer_id` int(10) unsigned,
  `customer_name` varchar(50) NOT NULL default '',
  `customer_company` varchar(50),
  `order_date` datetime,
  `delivery_time` datetime,
  `delivery_venue` varchar(50),
  `special_request` text,
  PRIMARY KEY  (`kitchen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  ");
 
$installer->endSetup();