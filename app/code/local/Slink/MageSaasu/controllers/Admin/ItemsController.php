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

class Slink_MageSaasu_Admin_ItemsController extends Mage_Adminhtml_Controller_Action
{   

	protected function indexAction() {        
		$this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('slink/admin_items_list'));
        $this->getLayout()->getBlock('head')->setTitle('Items');
        $this->renderLayout();
	}
	public function viewAction(){
		$this->loadLayout();
		$this->_addContent($this->getLayout()->createBlock('slink/admin_items_view'));
        $this->getLayout()->getBlock('head')->setTitle('View Item');
		$this->renderLayout();
	}	
	public function massLinkAction(){
		$config = Mage::getStoreConfig('slinksettings');
		try{
			if($ids = $this->getRequest()->getParam('items', false)){
				foreach($ids as $id){
					if(!($result = Mage::getModel('slink/items')->setId($id)->load($id)->link())==true)
						throw new Exception ($result);
					if($config['saasu']['debug']) echo '<br>ITEM '.$id.' - linked successfully.';
				}
				$this->_getSession()->addSuccess(Mage::helper('slink')->__('Item(s) linked successfully'));											
			}
		}catch(Exception $e){
			$this->_getSession()->addError($e->getMessage());
		}
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';				
	}
	public function linkAction(){
		$config = Mage::getStoreConfig('slinksettings');
		try{		
			if($id = $this->getRequest()->getParam('id', false)){
				if(($result = Mage::getModel('slink/items')->setId($id)->load($id)->link())==true){
					$this->_getSession()->addSuccess(Mage::helper('slink')->__('Item(s) linked successfully'));			
					if($config['saasu']['debug']) echo '<br>ITEM '.$id.' - linked successfully.';
				}else throw new Exception ($result);
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
			if($ids = $this->getRequest()->getParam('items', false)){
				foreach($ids as $id){
					$contact = Mage::getModel('slink/items')->setId($id)->load($id)->unlink();
					if($config['saasu']['debug']) echo '<br>ITEM '.$id.' - link removed successfully.';
				}
				$this->_getSession()->addSuccess(Mage::helper('slink')->__('Link(s) removed successfully'));											
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
				$item = Mage::getModel('slink/items')->setId($id)->load($id)->unlink();
				$this->_getSession()->addSuccess(Mage::helper('slink')->__('Link removed successfully'));			
				if($config['saasu']['debug']) echo '<br>ITEM '.$id.' - link removed successfully.';
			}
		}catch(Exception $e){
			$this->_getSession()->addError($e->getMessage());
		}
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));				
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';
		
	}	

}