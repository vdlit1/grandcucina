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

class Slink_MageSaasu_Admin_SchedulesController extends Mage_Adminhtml_Controller_Action
{
	protected function indexAction() {
		
		$this->loadLayout();
		$this->_addContent($this->getLayout()->createBlock('slink/admin_schedules_list'));
        $this->getLayout()->getBlock('head')->setTitle('Schedules');
		$this->renderLayout();
	}   

    public function newAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('slink/admin_schedules_edit'));
        $this->getLayout()->getBlock('head')->setTitle('View Schedule');        
        $this->renderLayout();
    }
	
    public function postAction()
    {
		$config = Mage::getStoreConfig('slinksettings');
        if ($data = $this->getRequest()->getPost()) {
            $schedule = Mage::getModel('slink/schedules')->setData($data);
            try {
                $schedule->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Schedule was successfully saved'));
				if($config['saasu']['debug']) echo '<br>Schedule '.$schedule->getData('id').' saved successfully';
            } catch (Exception $e){
                die($e->getMessage());
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				if($config['saasu']['debug']) echo '<br>Schedule '.$schedule->getData('id').' save failed';
            }
        }
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';		
    }    

    public function editAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('slink/admin_schedules_edit'));
        $this->renderLayout();
    }

    public function saveAction()
    {
		$config = Mage::getStoreConfig('slinksettings');
        if ($data = $this->getRequest()->getPost()) {		
            try {
				if(($schedule_id = $this->getRequest()->getParam('id', false))>0){
					$schedule = Mage::getModel('slink/schedules')->load($schedule_id)->addData($data);					
					$schedule->setId( $schedule_id )->save();					
					if($config['saasu']['debug']) echo '<br>Schedule '.$id.' saved successfully';
				}else{
					$schedule = Mage::getModel('slink/schedules')->setData($data);
					$schedule->save();										
					if($config['saasu']['debug']) echo '<br>Schedule '.$id.' saved successfully';					
				}				

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Schedule successfully saved'));
            } catch (Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				if($config['saasu']['debug']) echo '<br>Schedule '.$id.' save failed';
            }
        }
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';		
    }
	
	public function massDeleteAction(){
		$config = Mage::getStoreConfig('slinksettings');
		try{
			if($ids = $this->getRequest()->getParam('schedules', false)){
				foreach($ids as $id){
					if(Mage::getModel('slink/schedules')->setId($id)->load($id)->delete()){
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Schedule '.$id.' successfully deleted.'));
						if($config['saasu']['debug']) echo '<br>Schedule '.$id.' successfully deleted';
					}else{
						Mage::getSingleton('adminhtml/session')->addSSucess(Mage::helper('slink')->__('Schedule '.$id.' executed but contains errors.'));																			
						if($config['saasu']['debug']) echo '<br>Schedule '.$id.' delete failed.';						
					}
				}
			}
		}catch(Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());			
		}
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';				
		
	}
	public function deleteAction()
    {
		$config = Mage::getStoreConfig('slinksettings');
        $id = $this->getRequest()->getParam('id', false);
		
        try {
            Mage::getModel('slink/schedules')->setId($id)->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Schedule '.$id.' successfully deleted.'));
			if($config['saasu']['debug']) echo '<br>Schedule '.$id.' deleted successfully';
        } catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			if($config['saasu']['debug']) echo '<br>Schedule '.$id.' delete failed';			
        }		
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';		
    }
	public function massRunAction(){
		$config = Mage::getStoreConfig('slinksettings');
		try{
			if($ids = $this->getRequest()->getParam('schedules', false)){
				foreach($ids as $id){
					if(Mage::getModel('slink/schedules')->setId($id)->load($id)->run()){
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Schedule '.$id.' successfully run.'));
						if($config['saasu']['debug']) echo '<br>Schedule '.$id.' ran successfully';
					}else{
						Mage::getSingleton('adminhtml/session')->addSSucess(Mage::helper('slink')->__('Schedule '.$id.' executed but contains errors. '));																			
						if($config['saasu']['debug']) echo '<br>Schedul '.$id.' run failed';
					}
				}
			}
		}catch(Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());			
		}
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';				
		
	}
	
	public function runAction(){
		$id = $this->getRequest()->getParam('id', false);
		$config = Mage::getStoreConfig('slinksettings');
		try{
			$schedule = Mage::getModel('slink/schedules')->load($id);
			if($schedule->run()) {
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Schedule '.$id.' successfully run.'));			
				if($config['saasu']['debug']) echo '<br>Schedule '.$id.' ran successfully';
			}else{
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Schedule '.$id.' executed but contains errors. '));			
				if($config['saasu']['debug']) echo '<br>Schedule '.$id.' run failed';
			}
		}catch(Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());			
		}
     	if(!$config['saasu']['debug']) $this->_redirect('*/*/');
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';				
	}
	public function runAllAction(){
		$config = Mage::getStoreConfig('slinksettings');

		try{
			if(Mage::getModel('slink/schedules')->runAll()) {
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('All Schedule(s) successfully run.'));			
				if($config['saasu']['debug']) echo '<br>All Schedule(s) successfully run.';
			}else{
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Schedule executed but contains errors. '));			
				if($config['saasu']['debug']) echo '<br>Some or all schedule(s) run failed.';				
			}
		}catch(Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());			
		}
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';				
	}
	public function massPublishAction(){
		$config = Mage::getStoreConfig('slinksettings');
		try{
			if($ids = $this->getRequest()->getParam('schedules', false)){
				foreach($ids as $id){
					if(Mage::getModel('slink/schedules')->setId($id)->load($id)->publish(1)){
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Schedule '.$id.' is published.'));			
						if($config['saasu']['debug']) echo '<br>Schedule '.$id.' is now published';
					}else{
						Mage::getSingleton('adminhtml/session')->addError(Mage::helper('slink')->__('Schedule '.$id.' is not published.'));																			
						if($config['saasu']['debug']) echo '<br>Schedule '.$id.' publish failed';
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
			$script = Mage::getModel('slink/schedules')->setId($id)->load($id)->publish(1);
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Schedule '.$id.' is published.'));			
			if($config['saasu']['debug']) echo '<br>Schedule '.$id.' is now published';
        } catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			if($config['saasu']['debug']) echo '<br>Schedule '.$id.' publish failed';
        }		
		
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';		   
	}
	public function massUnpublishAction(){
		$config = Mage::getStoreConfig('slinksettings');
		try{
			if($ids = $this->getRequest()->getParam('schedules', false)){
				foreach($ids as $id){
					if(Mage::getModel('slink/schedules')->setId($id)->load($id)->publish(0)){
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Schedule '.$id.' is unpublished.'));			
						if($config['saasu']['debug']) echo '<br>Schedule '.$id.' is now unpublished';
					}else{
						Mage::getSingleton('adminhtml/session')->addError(Mage::helper('slink')->__('Schedule '.$id.' is not unpublished.'));																			
						if($config['saasu']['debug']) echo '<br>Schedule '.$id.' unpublish failed';
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
		$id = $this->getRequest()->getParam('id', false);
		$config = Mage::getStoreConfig('slinksettings');
		try{
			$script = Mage::getModel('slink/schedules')->setId($id)->load($id)->publish(0);
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slink')->__('Schedule '.$id.' is unpublished.'));			
			if($config['saasu']['debug']) echo '<br>Schedule '.$id.' is now unpublished';
        } catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			if($config['saasu']['debug']) echo '<br>Schedule '.$id.' unpublish failed';
        }		
		
     	if(!$config['saasu']['debug']) $this->getResponse()->setRedirect($this->getUrl('*/*/'));			
		else echo '<br><a href="'.$this->getUrl('*/*/').'">Return</a>';		   
	}
}