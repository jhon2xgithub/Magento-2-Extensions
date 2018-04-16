<?php
  
$installer = $this;
  
$installer->startSetup();
  
$installer->run("
  
-- DROP TABLE IF EXISTS {$this->getTable('storesquare_tb')};
	CREATE TABLE {$this->getTable('storesquare_tb')} (
	  	`ID` int(11) unsigned NOT NULL auto_increment,
	  	`STORESQUARE_CATEGORY_ID` int(11) NOT NULL default '0',
	  	`STORESQUARE_NAME` VARCHAR(255) NOT NULL default '',
	 	`MAGENTO_CATEGORY_ID` int(11) NOT NULL default '0',  
	  	PRIMARY KEY (`ID`)
	) 	ENGINE=InnoDB DEFAULT CHARSET=utf8;

	ALTER TABLE cron_schedule ADD `is_exported` INT(11) NOT NULL;
	  
");


$installer->endSetup(); 