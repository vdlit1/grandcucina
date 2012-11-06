<?php
/**
 * Observer Model
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
 * @category   Sales
 * @package    Slink
 * @copyright  Copyright (c) 2012 Saaslink
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Bernard Tai bernard@saaslink.com
 */

class Slink_Magesaasu_Model_Items_Observer{

	public function create($observer){
        $this->update($observer);

	}
    public function update($observer){

        $event = $observer->getEvent();
        $product = $event->getProduct();
        $config = Mage::getStoreConfig('slinksettings');
        
        $slink_item = Mage::getModel('slink/items')->load($product->getData('entity_id'), 'vid');
        try{
            if($slink_item->getId()<1){

                $slink_item->setData(array('vid'=>$product->getData('entity_id')))->save();

                /* Pookinuk */
                /* End */
            }
            if($config['items']['linkOnCreateUpdate']) $slink_item->link();
            
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
        }
    }
    
    public function delete($observer){
        $event = $observer->getEvent();
        $product = $event->getProduct();
        
        $slink_item = Mage::getModel('slink/items')->load($product->getData('entity_id'), 'vid');
        try{
            if(($id = $slink_item->getId()) && $id>0){
                $slink_item->setId($id)->delete();
            }
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
        }    
    }    
}
