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

	
class Slink_MageSaasu_Admin_ContactsController extends Mage_Adminhtml_Controller_Action
{
	protected function indexAction() {
        
		$this->loadLayout();
		$this->_addContent($this->getLayout()->createBlock('slink/admin_contacts_list'));
        $this->getLayout()->getBlock('head')->setTitle('Contacts');
		$this->renderLayout();
        

	}
	public function viewAction(){
		$this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('slink/admin_contacts_view'));
        $this->getLayout()->getBlock('head')->setTitle('View Contact');        
		$this->renderLayout();
	}	
	public function massLinkAction(){
		$config = Mage::getStoreConfig('slinksettings');		
		try{
			if($ids = $this->getRequest()->getParam('contacts', false)){
				foreach($ids as $id){
					if(!($result = Mage::getModel('slink/contacts')->load($id)->link($id))==true)
						throw new Exception ($result);
					if($config['saasu']['debug']) echo '<br>CONTACT '.$id.' - linked successfully.';
				}
				$this->_getSession()->addSuccess(Mage::helper('slink')->__('Contact(s) linked successfully'));											
			}
		}catch(Exception $e){
			$this->_getSession()->addError($e->getMessage());
		}

     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';		
		
	}
	public function linkAction(){
        $id = $this->getRequest()->getParam('id');
        
		$config = Mage::getStoreConfig('slinksettings');
		try{	
            $slink_contact = Mage::getModel('slink/contacts')->load($id);
			if($slink_contact->getId()>0){
                $slink_contact->link();
				$this->_getSession()->addSuccess(Mage::helper('slink')->__('Contact linked successfully'));							
				if($config['saasu']['debug']) echo '<br>CONTACT '.$id.' - linked successfully.';				
			}
		}catch(Exception $e){
			$this->_getSession()->addError($e->getMessage());
		}

     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';		
	}
	public function massUnlinkAction(){
		$config = Mage::getStoreConfig('slinksettings');
		try{
			if($ids = $this->getRequest()->getParam('contacts', false)){
				foreach($ids as $id){
					$contact = Mage::getModel('slink/contacts')->load($id)->unlink($id);
					if($config['saasu']['debug']) echo '<br>CONTACT '.$id.' - link removed successfully.';					
				}
				$this->_getSession()->addSuccess(Mage::helper('slink')->__('Contact(s) un-linked successfully'));											
			}
		}catch(Exception $e){
			$this->_getSession()->addError($e->getMessage());
		}
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';
		
	}
	public function unlinkAction(){
		$config = Mage::getStoreConfig('slinksettings');
		try{
			if($id = $this->getRequest()->getParam('id', false)){
				$contact = Mage::getModel('slink/contacts')->load($id)->unlink($id);
				$this->_getSession()->addSuccess(Mage::helper('slink')->__('Link removed successfully'));			
				if($config['saasu']['debug']) echo '<br>CONTACT '.$id.' - link removed successfully.';				
			}
		}catch(Exception $e){
			$this->_getSession()->addError($e->getMessage());
		}
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';		
	}		

}