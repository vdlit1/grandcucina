<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('managebrands')};
CREATE TABLE {$this->getTable('managebrands')} (
  `managebrands_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `option_id` smallint(6) NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `description` text NOT NULL default '',
  `products` varchar(255) NOT NULL default '',
  `static` varchar(255) NOT NULL default '',
  `static1` varchar(255) NOT NULL default '',
  `static2` varchar(255) NOT NULL default '',
  `static3` varchar(255) NOT NULL default '',
  PRIMARY KEY (`managebrands_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 