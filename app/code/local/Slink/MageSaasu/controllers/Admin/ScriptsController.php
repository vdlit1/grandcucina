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
 * @package    Schedules
 * @copyright  Copyright (c) 2009 Bernard Tai
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Bernard Tai bernard.tai@saaslink.net
 */
	
class Slink_MageSaasu_Admin_ScriptsController extends Mage_Adminhtml_Controller_Action
{
	protected function indexAction() {
		
		$this->loadLayout();
		$this->_addContent($this->getLayout()->createBlock('slink/admin_scripts_list'));
        $this->getLayout()->getBlock('head')->setTitle('Scripts');
		$this->renderLayout();
	}   

    public function newAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('slink/admin_scripts_edit'));
        $this->getLayout()->getBlock('head')->setTitle('View Script');
        $this->renderLayout();
    }
    public function editAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('slink/admin_scripts_edit'));
        $this->getLayout()->getBlock('head')->setTitle('View Script');
        $this->renderLayout();
    }	
	
    public function saveAction()
    {
		$config = Mage::getStoreConfig('slinksettings');
        if ($data = $this->getRequest()->getPost()) {		
            try {			
				$script = Mage::getModel('slink/scripts')->setData($data);				
				if(($script_id = $this->getRequest()->getParam('id', false))>0){
					// Do nothing
					$script->setId( $script_id )->save();					
				}else{
					$script_id = $this->getRequest()->getParam('id');

					if($script->process_script()) {
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Script saved'));						
						if($config['saasu']['debug']) echo '<br>Script '.$script_id.' saved successfully';
					}
				}				
            } catch (Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				if($config['saasu']['debug']) echo '<br>Script '.$script_id.' save failed';
            }
        }
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';
    }
	public function massDeleteAction(){
		$config = Mage::getStoreConfig('slinksettings');
		try{
			if($ids = $this->getRequest()->getParam('scripts', false)){
				foreach($ids as $id){
					$script = Mage::getModel('slink/scripts')->setId($id)->load($id);
					if($script->delete_script()){
						$script->delete();
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Script '.$id.' successfully deleted.'));									
						if($config['saasu']['debug']) echo '<br>Script '.$id.' deleted successfully';
					}else{
						Mage::getSingleton('adminhtml/session')->addError(Mage::helper('slink')->__('Script '.$id.' is not deleted.'));																			
						if($config['saasu']['debug']) echo '<br>Script '.$id.' delete failed';
					}
				}				

			}
		}catch(Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());			
			if($config['saasu']['debug']) echo '<br>Script(s) delete failed';
		}
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';				
	}
	
	public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id', false);
		$config = Mage::getStoreConfig('slinksettings');
		
        try {
			if($id>0){
				$script = Mage::getModel('slink/scripts')->setId($id)->load($id);
				
				if ($script->delete_script()){
					$script->setId($id)->delete();
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Script '.$id.' successfully deleted.'));
					if($config['saasu']['debug']) echo '<br>Script '.$id.' deleted successfully';
				}
			}
        } catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			if($config['saasu']['debug']) echo '<br>Script '.$id.' delete failed';
        }		
		
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';		   
	}
	public function massPublishAction(){
		$config = Mage::getStoreConfig('slinksettings');
		try{
			if($ids = $this->getRequest()->getParam('scripts', false)){
				foreach($ids as $id){
					if(Mage::getModel('slink/scripts')->setId($id)->load($id)->publish(1)){
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Script '.$id.' is published.'));			
						if($config['saasu']['debug']) echo '<br>Script '.$id.' published successfully';
					}else{
						Mage::getSingleton('adminhtml/session')->addError(Mage::helper('slink')->__('Script '.$id.' is not published.'));																			
						if($config['saasu']['debug']) echo '<br>Script '.$id.' publish failed';
					}
				}
			}
        } catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }		
		
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';		   
	}	
	
	public function publishAction(){
		$config = Mage::getStoreConfig('slinksettings');
		$id = $this->getRequest()->getParam('id', false);
		try{
			$script = Mage::getModel('slink/scripts')->setId($id)->load($id)->publish(1);
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Script '.$id.' is published.'));			
			if($config['saasu']['debug']) echo '<br>Script '.$id.' published successfully';
        } catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			if($config['saasu']['debug']) echo '<br>Script '.$id.' publish failed';
        }		
		
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';		   
	}
	public function massUnpublishAction(){
		$config = Mage::getStoreConfig('slinksettings');
		try{
			if($ids = $this->getRequest()->getParam('scripts', false)){
				foreach($ids as $id){
					if(Mage::getModel('slink/scripts')->setId($id)->load($id)->publish(0)){
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Script '.$id.' is unpublished.'));			
						if($config['saasu']['debug']) echo '<br>Script '.$id.' unpublished successfully';
					}else{
						Mage::getSingleton('adminhtml/session')->addError(Mage::helper('slink')->__('Script '.$id.' is not unpublished.'));																			
						if($config['saasu']['debug']) echo '<br>Script '.$id.' unpublish failed';
					}
				}
			}
        } catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }		
		
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';		   
	}		
	
	public function unpublishAction(){
		$config = Mage::getStoreConfig('slinksettings');
		$id = $this->getRequest()->getParam('id', false);
		try{
			$script = Mage::getModel('slink/scripts')->setId($id)->load($id)->publish(0);
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Script '.$id.' is unpublished.'));			
			if($config['saasu']['debug']) echo '<br>Script '.$id.' unpublished successfully';
        } catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			if($config['saasu']['debug']) echo '<br>Script '.$id.' unpublish failed';
        }		
		
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';		   
	}
}