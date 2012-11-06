<?php

class Slink_MageSaasu_Block_Admin_Sales_View extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{		
		parent::__construct();
		
		$this->_blockGroup = 'slink';
		$this->_controller = 'admin_sales';
		$this->_mode = 'view';
		
		if( $this->getRequest()->getParam($this->_objectId) ) {
			$slink_sale = Mage::getModel('slink/sales')->load($this->getRequest()->getParam($this->_objectId));
			
			$sale = Mage::getModel('sales/order')->load($slink_sale->getData('vid'));
			
			$config = Mage::getStoreConfig('slinksettings');
			$tags = explode(' , ', $config['sales']['saleTags']);
			$tags[] = $sale->getData('coupon_code');
							
			Mage::register('current_sale', $sale);
			Mage::register('slink_id', $this->getRequest()->getParam($this->_objectId));
			Mage::register('billing', $sale->getBillingAddress());
			Mage::register('shipping', $sale->getShippingAddress());			
			Mage::register('invoice_number', $slink_sale->getData('invoice_number'));
			Mage::register('transferred', Mage::getModel('core/date')->date('Y-m-d H:i:s', $slink_sale->getData('transferred')));
			Mage::register('sale_tags', implode(', ', $tags));			
		}
		$this->_removeButton('save');
		$this->_removeButton('delete');			
		$this->_removeButton('reset');			

	}			
		
	public function getHeaderText()
	{
		return Mage::helper('slink')->__("View Sale");			
	}
}
