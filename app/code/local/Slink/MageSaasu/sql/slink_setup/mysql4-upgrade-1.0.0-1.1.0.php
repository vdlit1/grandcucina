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
 * @copyright  Copyright (c) 2009 Bernard Tai
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Bernard Tai bernard.tai@saaslink.net
 */

$installer = $this;

$installer->startSetup();

$installer->run("
				
ALTER TABLE {$this->getTable('slink_contacts')} 
ADD COLUMN `address_type` int(11) NOT NULL default 0 AFTER `vid`; 				

ALTER TABLE {$this->getTable('slink_contacts')} 
CHANGE COLUMN `vid` `vid` int(11) NOT NULL default 0;
				
");

$installer->endSetup();
