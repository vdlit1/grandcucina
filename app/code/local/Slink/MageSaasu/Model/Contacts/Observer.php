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

class Slink_Magesaasu_Model_Contacts_Observer{

	public function create($observer){
        $this->update($observer);

	}
    public function update($observer){
        $event = $observer->getEvent();
        $customer = $event->getCustomer();
        $config = Mage::getStoreConfig('slinksettings');
                                                            
        $addresses = Mage::getModel('customer/customer')->load($customer->getId())->getAddressesCollection();

        try{                
            foreach($addresses as $address){
                $slink_contact = Mage::getModel('slink/contacts')->load($address->getId(), 'vid');
                if($slink_contact->getId()<1){
                    $slink_contact->setData(array('vid'=>$address->getId()))->save();
                    

                }

                /* Pookinuk */
                if($config['contacts']['linkOnCreateUpdate']) $slink_contact->link();
                /* End */
                

            }
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
        }
        
    }
    /* Delete addresses only if Magento Customer is deleted. */
    public function delete($observer){
        $event = $observer->getEvent();
        $customer = $event->getCustomer();
    
        $addresses = Mage::getModel('customer/customer')->load($customer->getId())->getAddressesCollection();        
    
        try{
            foreach($addresses as $address){
                $slink_contact = Mage::getModel('slink/contacts')->load($address->getId(), 'vid');            
                if(($id = $slink_contact->getId()) && $id>0){
                    $slink_contact->setId($id)->delete();
                }            
            }
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
        }
        
        /* Pookinuk */
        /* TODO link after creating Slink record */
        /* End */
    }
    
}
