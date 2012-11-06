<?php
class Atd_Quicklook_IndexController extends Mage_Core_Controller_Front_Action
{

    public function productAction()
    {
		$productId  = (int) $this->getRequest()->getParam('id');
//                echo $productId; die;
		$_product = Mage::getModel('catalog/product')->load($productId);
		//echo '<pre>'; print_r(); 
		//echo  $_product->getName(); die();
		if($productId)
			Mage::register('quickview_product', $_product);
			
			//echo '<pre>'; print_r(Mage::registry('quickview_product')); die();
		Mage::app()->getFrontController()->getResponse()->setBody($this->renderMiniView());
        /* $json = Mage::getBlockSingleton('quicklook/quicklook');
    	 $response = Mage::getModel('quicklook/quicklook');
         $response->setMiniview($this-> renderMiniView());
                           //->setJsonconfig($json-> getJsonConfig());
         $response->send(); */
    }
    public function renderMiniView(){
         return  Mage::getSingleton('core/layout')
                ->createBlock('quicklook/quicklook')
                ->setTemplate('quicklook/mini-view.phtml')
                ->renderView();
    }
}