<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('attributemanager')};
CREATE TABLE {$this->getTable('attributemanager')} (
  `attributemanager_id` int(11) unsigned NOT NULL auto_increment,
  `attribute_code` varchar(255) NOT NULL default '',
  `option_id` int(10),
  `filename` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `attribute_decription` text NOT NULL default '',
  PRIMARY KEY (`attributemanager_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 