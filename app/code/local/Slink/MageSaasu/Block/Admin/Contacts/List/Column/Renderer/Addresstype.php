<?php
	class Slink_MageSaasu_Block_Admin_Contacts_List_Column_Renderer_Addresstype extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
	{
		public function render(Varien_Object $row){		
			$address = Mage::getModel('customer/address')->load($row->vid);
			$customer = Mage::getModel('customer/customer')->load($address->parent_id);
			
			if($row->vid == $customer->default_billing){
				return 'Billing';
			}elseif($row->vid == $customer->default_shipping){
				return 'Shipping';
			}else{
				return 'Additional';
			}

		}
	}
