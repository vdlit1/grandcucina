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
 * @package    Items
 * @copyright  Copyright (c) 2009 Bernard Tai
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Bernard Tai bernard.tai@saaslink.net
 */

class Slink_MageSaasu_Model_Items extends Mage_Core_Model_Abstract
{
	
    protected function _construct()
    {
		parent::_construct();
        $this->_init('slink/items');		
    }
	
	public function link(){

        $item = Mage::getModel('catalog/product')->load($this->getData('vid'));

		if($item->getId()>0){

            $slink_item = Mage::getModel('slink/saasu_item');                        
        
            /* Look for item if UID available */
            if($this->getData('entity_uid')<>"" && $this->getData('updated_uid')<>"") $slink_item->get($this->getData('entity_uid'));

            /* Look for item by SKU if not able to get by UID */
            if($slink_item->getData('uid')=="" && $slink_item->getData('lastUpdatedUid')=="") $slink_item->getBySku($item->getSku());
        
            /* Post if not able to get by SKU */
            if(($slink_item->getData('uid')=="" && $slink_item->getData('lastUpdatedUid')=="") 
               || /* If item has been updated - post anyway */
               $this->getData('transferred') < $item->getData('updated_at')) {
                
                $config = Mage::getStoreConfig('slinksettings/items');
                /** Post new item or update item if allowed */
                if($config['enableCreateUpdate']){
                    
                    $slink_item->setItem( $item );
                    
                    /* Creating new item... */
                    /* Set Stock levels if required */
                    $item_stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($item);
					
                    $isInventoried = ($item_stock->getData('manage_stock') ? 'true':'false');
					
					/* Grand Cucina */
					$isInventoried = 'false';
					/* End */
					
                    $slink_item->setData('isInventoried', $isInventoried);
                    if($isInventoried=='true') {
                        $slink_item->setData('stockOnHand', $item_stock->getData('qty'));
                        $slink_item->setData('assetAccountUid', $slink_item->getData('_defaultAssetAccountUid'));
                        $slink_item->setData('saleCoSAccountUid', $slink_item->getData('_defaultCoSAccountUid'));
                    }
                    
                    /* Item is Enabled */
                    $isActive = ($item->getData('status') ? 'true' : 'false');
                    $slink_item->setData('isActive', $isActive);
					
                    /* Set default notes */
                    $slink_item->setData('notes', $config['items']['itemNotes']);
                    
                    /* Set Accounts */
                    $slink_item->setData('purchaseExpenseAccountUid', $slink_item->getData('_defaultExpenseAccountUid'));
                    $slink_item->setData('saleIncomeAccountUid', $slink_item->getData('_defaultIncomeAccountUid'));
                    
                    /* Set Buy / Sell Prices */
                    $slink_item->setData('isSold', ($slink_item->getData('_defaultIncomeAccountUid')<>"" ? 'true' : 'false'));
                    $slink_item->setData('isBought', ($slink_item->getData('_defaultExpenseAccountUid')<>"" ? 'true' : 'false'));
                    $slink_item->setData('buyingPrice', ($slink_item->getData('_defaultExpenseAccountUid')<>"" ? $item->getData('cost') : '0'));
					
					/* Grand Cucina */
					$cost = ($item->getData('grandcucina_unit_cost') > 0 ? $item->getData('grandcucina_unit_cost') : $item->getData('cost'));
					$slink_item->setData('buyingPrice', ($slink_item->getData('_defaultExpenseAccountUid')<>"" ? $cost : 0));
					/* End */
					
					/* Set Tax codes */
					$slink_item->setData('purchaseTaxCode', $slink_item->getData('_defaultPurchaseTaxCode'));
					$slink_item->setData('saleTaxCode', $slink_item->getData('_defaultSaleTaxCode'));
					
                    if($slink_item->post()) $slink_item->setData('transferred' , Mage::getModel('core/date')->gmtTimestamp());
					
                }else throw new Exception('Cannot link item ('.$item->getSku().'). Check Slink Items or settings "Item create / update" disabled. ');
            }
            
			if(($uid = $slink_item->getData('uid')) && ($lastUpdatedUid = $slink_item->getData('lastUpdatedUid'))){
				$this->addData(array('entity_uid'=>$uid,
									 'updated_uid'=>$lastUpdatedUid,
									 'transferred'=>$slink_item->getData('transferred')
                                     ));

                if(!$this->getData('transferred'))
                    $this->setData('transferred', Mage::getModel('core/date')->gmtTimestamp());
                
				$this->save();
			}

			if(($errors = $slink_item->getError()) && count($errors) > 0){
                $message = ''; 	                
				foreach($errors as $error){
					$message .= 'Item '.$item->getData('sku').' ( ID '.$item->entity_id.' ) - '.$error['code']. ' - '.$error['message'].' ( '.$error['action'].' ) <br>';
				}
                if($message<>"") throw new Exception( $message );                
			}			
			return true;
            
		}else return false;
		

	}
	public function unlink(){
		if($this->getData('id') > 0) {
			$this->addData(array('entity_uid'=>'',
								 'updated_uid'=>'',
								 'transferred'=>''));
			$this->save();
			return;
		}else throw new Exception ('No Item with ID '.$this->getId());		
	}	
}