<?php
class Itech_Managebrands_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		if($id=$this->getRequest()->getParam('id')){
			$brand = Mage::getModel('managebrands/managebrands')->load($id);
			if(!$brand->getId()){
				$this->_redirectUrl(Mage::getBaseUrl());
			}
			$title = $this->__('%s - Brand', $brand->getTitle());
			$this->loadLayout();
			$this->getLayout()->getBlock('head')->setTitle($title);
			$this->renderLayout();
		}
		else{
			$this->_redirectUrl(Mage::getBaseUrl());
		}
    }
}