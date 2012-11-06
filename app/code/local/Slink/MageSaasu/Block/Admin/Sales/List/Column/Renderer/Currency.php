<?php
	class Slink_MageSaasu_Block_Admin_Sales_List_Column_Renderer_Currency extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
	{
		public function render(Varien_Object $row){
			$currency = Mage::getModel('sales/order')->load($row->getData('vid'))->getData('order_currency_code');
			return $currency;
			
		}
	}
