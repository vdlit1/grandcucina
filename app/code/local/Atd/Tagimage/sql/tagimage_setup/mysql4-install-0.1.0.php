<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS `{$this->getTable('tagimage')}`;
CREATE TABLE `{$this->getTable('tagimage')}` (
  `tagimage_id` int(11)  NOT NULL auto_increment,
  `label` varchar(255) DEFAULT NULL,
  `categories` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `position` smallint(5) DEFAULT '0',
  `disabled` tinyint(1) DEFAULT '1',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`tagimage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- DROP TABLE IF EXISTS `{$this->getTable('tags')}`;
CREATE TABLE `{$this->getTable('tags')}` (
  `tags_id` int(11)  NOT NULL auto_increment,
  `image_id` int(11) NOT NULL,
  `sku`     varchar(255) DEFAULT NULL,
  `height`  int(11) DEFAULT NULL,
  `width`   int(11) DEFAULT NULL,
  `top`     int(11) DEFAULT NULL,
  `left`    int(11) DEFAULT NULL,
  `label`   varchar(255) DEFAULT NULL,
   PRIMARY KEY (`tags_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;



-- DROP TABLE IF EXISTS `{$this->getTable('page')}`;
    CREATE TABLE `{$this->getTable('page')}` (
      `page_id` int(11)  NOT NULL,
      `page` varchar(255) NOT NULL,
       PRIMARY KEY (`page_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

");




$installer->endSetup(); 