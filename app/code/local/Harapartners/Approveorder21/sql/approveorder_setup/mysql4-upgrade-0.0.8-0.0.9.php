<?php
$installer = $this;
$installer->startSetup();
 

$installer->run("

ALTER TABLE {$this->getTable('kitchen')} ADD COLUMN `special_request` TEXT DEFAULT '' AFTER `request`;

");

$installer->endSetup();
?>