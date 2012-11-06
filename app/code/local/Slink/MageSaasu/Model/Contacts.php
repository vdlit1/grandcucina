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
 * @package    Contacts
 * @copyright  Copyright (c) 2009 Bernard Tai
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Bernard Tai bernard.tai@saaslink.net
 */

class Slink_MageSaasu_Model_Contacts extends Mage_Core_Model_Abstract
{
	
    protected function _construct()
    {
		parent::_construct();
        $this->_init('slink/contacts');		
    }

	public function link(){
        
        $address = Mage::getModel('customer/address')->load($this->getData('vid'));
		$customer = Mage::getModel('customer/customer')->load($address->getData('parent_id'));
				
		if($address->getId()>0){

            $slink_contact = Mage::getModel('slink/saasu_contact');

			if($address->getData('entity_id') == $customer->getData('default_billing')){
				$contactId = 'BILLING'.$address->getData('entity_id');
			}elseif($address->getData('entity_id') == $customer->getData('default_shipping')){
				$contactId = 'SHIPPING'.$address->getData('entity_id');
			}else $contactId = 'ADDRESS'.$address->getData('entity_id');

            if($this->getData('entity_uid')<>"" && $this->getData('updated_uid')<>"") $slink_contact->get($this->getData('entity_uid'));

            if($slink_contact->getData('uid')=="" && $slink_contact->getData('lastUpdatedUid')=="")
				$slink_contact->getByContactId($contactId);
				
            if(($slink_contact->getData('uid')=="" && $slink_contact->getData('lastUpdatedUid')=="")
               || $this->getData('transferred') < $address->getData('updated_at')) {
                
				$config = Mage::getStoreConfig('slinksettings/contacts') ;
                
                $slink_contact->setContact($address);
				$slink_contact->setData('contactId', $contactId );
                $slink_contact->setData('tags', $config['contactTags']);
				
				if($address->getData('entity_id')==$customer->getData('default_billing')) $slink_contact->setData('email', $customer->getData('email'));
                
                if($slink_contact->post()){
                    $slink_contact->setData('transferred' , Mage::getModel('core/date')->gmtTimestamp());                    
                }				
			}
            
            $errors = $slink_contact->getError();
            if(count($errors)==0 && ($entity_uid = $slink_contact->getData('uid')) && ($updated_uid = $slink_contact->getData('lastUpdatedUid'))){
                $this->addData(array('entity_uid'=>$entity_uid,
                                     'updated_uid'=>$updated_uid,
                                     'transferred'=>$slink_contact->getData('transferred'))
                               );
                
                if(!$this->getData('transferred'))
                    $this->setData('transferred', Mage::getModel('core/date')->gmtTimestamp());
                
                $this->save();
                return true;
            }else{
                foreach($errors as $error){
                    $message .= 'Contact '.$address->lastname.' ( '.$error['code']. ' - '.$error['message'].' ( '.$error['action'].' ) <br>';
                }
                if($message<>"") throw new Exception($message);
                return false;
            }
			
		} else return false;
		
	}
	public function unlink(){
		if($this->getData('id') > 0){	
			$this->addData(array('entity_uid'=>'',
								 'updated_uid'=>'',
								 'transferred'=>''));
			$this->save();
			return;
		}else throw new Exception ('No Contact with ID '.$this->getId());
	}
}