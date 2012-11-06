<?php
	class Slink_MageSaasu_Block_Admin_Sales_List_Column_Renderer_Billing extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
	{
		public function render(Varien_Object $row){
			$address = Mage::getModel('sales/order')->load($row->vid)->getBillingAddress();
			return ($address['company']=="" ? '<strong>'.$address['lastname'].'</strong>, '.$address['firstname'] : $address['company'].' [<strong>'.$address['lastname'].'</strong>, '.$address['firstname'].']');		}
	}
