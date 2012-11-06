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
	
class Slink_MageSaasu_Helper_Data extends Mage_Core_Helper_Abstract
{
	const SCRIPT_DIR = 'app/code/local/Slink/MageSaasu/scripts';
	const LOG_DIR = 'app/code/local/Slink/MageSaasu/logs';
	const MODEL_DIR = 'app/code/local/Slink/MageSaasu/Model';	
	
	const LOG_SALES_FILE = 'sales.log';	
	const LOG_ITEMS_FILE = 'items.log';
	const LOG_CONTACTS_FILE = 'contacts.log';	
	const LOG_SCHEDULES_FILE = 'schedules.log';
	const LOG_SCRIPTS_FILE = 'scripts.log';	
	
	
	function date($timestamp){
		return (($timestamp>0) ? date('Y-m-d H:i:s', (int)$timestamp) : '' );
	}
	function getPath($name){
		$path='';
		switch($name){
			case 'models':$path = Mage::getBaseDir().'/'.self::MODEL_DIR; break;
			case 'scripts':$path = Mage::getBaseDir().'/'.self::SCRIPT_DIR; break;
			case 'logs':$path = Mage::getBaseDir().'/'.self::LOG_DIR; break;
			default: break;
		}
		return $path;
	}
	
	function getLogFilePath($name){
		$filename = '';
		switch($name){
			case 'sales':$filename = Mage::getBaseDir().self::LOG_DIR.'/'.self::LOG_SALES_FILE; break;
			case 'items':$filename = Mage::getBaseDir().self::LOG_DIR.'/'.self::LOG_ITEMS_FILE; break;
			case 'contacts':$filename = Mage::getBaseDir().self::LOG_DIR.'/'.self::LOG_CONTACTS_FILE; break;
			case 'schedules':$filename = Mage::getBaseDir().self::LOG_DIR.'/'.self::LOG_SCHEDULES_FILE; break;
			case 'scripts':$filename = Mage::getBaseDir().self::LOG_DIR.'/'.self::LOG_SCRIPTS_FILE; break;
			default: break;
		}
		return $filename;		
		
	}
    
    function debug($message){
        
    }
    
    /** Reference: Ben Robie http://codemagento.com/2011/03/joining-an-eav-table-to-flat-table/ */
    public function joinEavTablesIntoCollection($collection, $mainTableForeignKey, $eavType){
        
        $entityType = Mage::getModel('eav/entity_type')->loadByCode($eavType);
        $attributes = $entityType->getAttributeCollection();
        $entityTable = $collection->getTable($entityType->getEntityTable());
        
        //Use an incremented index to make sure all of the aliases for the eav attribute tables are unique.
        $index = 1;
        foreach ($attributes->getItems() as $attribute){
            $alias = 'table'.$index;
            if ($attribute->getBackendType() != 'static'){
                $table = $entityTable. '_'.$attribute->getBackendType();
                $field = $alias.'.value';
                $collection->getSelect()
                ->joinLeft(array($alias => $table),
                           'main_table.'.$mainTableForeignKey.' = '.$alias.'.entity_id and '.$alias.'.attribute_id = '.$attribute->getAttributeId(),
                           array($attribute->getAttributeCode() => $field)
                           );
            }
            $index++;
        }
        //Join in all of the static attributes by joining the base entity table.
        $collection->getSelect()->joinLeft($entityTable, 'main_table.'.$mainTableForeignKey.' = '.$entityTable.'.entity_id');
        
        return $collection;
        
    }
    
}