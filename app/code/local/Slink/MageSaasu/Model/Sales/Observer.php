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

class Slink_Magesaasu_Model_Sales_Observer{

	public function create($observer){
        $this->update($observer);

	}
    public function update($observer){
        $event = $observer->getEvent();
        $order = $event->getOrder();
        $config = Mage::getStoreConfig('slinksettings');

        $slink_order = Mage::getModel('slink/sales')->load($order->getData('entity_id'), 'vid');

        try{
            if($slink_order->getId()<1){
                $slink_order->setData(array('vid'=>$order->getData('entity_id')))->save();
            }
            
            /* Pookinuk */
            if($config['sales']['linkOnCreateUpdate'])$slink_order->link();
            /* End */
            
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
        }
        
    }
}
