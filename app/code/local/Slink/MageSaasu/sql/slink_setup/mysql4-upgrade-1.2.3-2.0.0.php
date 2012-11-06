<?php
/**
 * Slink for Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * @category   Slink_MageSaasu
 * @package    
 * @copyright  Copyright (c) 2010 Bernard Tai
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Bernard Tai bernard.tai@saaslink.net
 */

$installer = $this;
$installer->startSetup();
$installer->run("
											
ALTER TABLE {$this->getTable('slink_schedules')} MODIFY `last_run` datetime NULL;

CREATE TABLE IF NOT EXISTS {$this->getTable('slink_tax_code')} (
`id` int(11) unsigned NOT NULL auto_increment,                                                                
`entity_uid` varchar(11) default NULL,
`name` varchar(255) default NULL,                                                                
`code` varchar(255) default NULL,
`updated_at` datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$this->getTable('slink_transaction_category')} (
`id` int(11) unsigned NOT NULL auto_increment,
`entity_uid` varchar(11) default NULL,
`type` varchar(255) default NULL,                                                                            
`name` varchar(255) default NULL,                                                                            
`updated_at` datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
CREATE TABLE IF NOT EXISTS {$this->getTable('slink_bank_account')} (
`id` int(11) unsigned NOT NULL auto_increment,
`entity_uid` varchar(11) default NULL,
`type` varchar(255) default NULL,                                                                            
`name` varchar(255) default NULL,                                                                            
`updated_at` datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                
CREATE TABLE IF NOT EXISTS {$this->getTable('slink_config_contact')} (
`id` int(11) unsigned NOT NULL auto_increment,
`entity_uid` varchar(11) default NULL,                                            
`name` varchar(255) default NULL,                                                                            
`updated_at` datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;                
                   
");
$installer->endSetup();
