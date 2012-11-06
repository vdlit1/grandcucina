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
				
CREATE TABLE IF NOT EXISTS {$this->getTable('slink_scripts')} (
`id` int(11) unsigned NOT NULL auto_increment,
`title` varchar(255) default '',
`description` varchar(255) default '',
`file` varchar(255) NOT NULL default '',	
`published` tinyint(1) NOT NULL default 1,
`version` varchar(11) default '',
`created` datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
								
CREATE TABLE IF NOT EXISTS {$this->getTable('slink_schedules')} (
`id` int(11) unsigned NOT NULL auto_increment,
`schedule_name` varchar(255) NOT NULL default '',
`schedule_file` varchar(255) NOT NULL default '',
`script_limit` int(11) NOT NULL default 1,
`mhdmd` varchar(255) NOT NULL default '',
`log_email` varchar(255) default NULL,
`log_file` varchar(255) default NULL,
`published` tinyint(1) NOT NULL default 1,
`last_run` datetime NOT NULL default '0000-00-00 00:00:00',
`created` datetime NOT NULL default '0000-00-00 00:00:00',
`sort_order` int(10) NOT NULL default 0,				
PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$this->getTable('slink_sales')} (
`id` int(11) unsigned NOT NULL auto_increment,
`vid` int(11) NOT NULL default 0,
`entity_uid` varchar(11) default NULL,
`updated_uid` varchar(128) default NULL,
`invoice_number` varchar(50) default NULL,
`transferred` datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$this->getTable('slink_items')} (
`id` int(11) unsigned NOT NULL auto_increment,
`vid` int(11) NOT NULL default 0,
`entity_uid` varchar(11) default NULL,
`updated_uid` varchar(128) default NULL,
`transferred` datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;				
				
CREATE TABLE IF NOT EXISTS {$this->getTable('slink_contacts')} (
`id` int(11) unsigned NOT NULL auto_increment,
`vid` int(11) NOT NULL default 0,
`entity_uid` varchar(11) default NULL,
`updated_uid` varchar(128) default NULL,
`transferred` datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		
");
$installer->endSetup();
