<?php
	class Slink_MageSaasu_Block_Admin_Contacts_List_Column_Renderer_Contactname extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
	{
		public function render(Varien_Object $row){
		
			$contact = Mage::getModel('customer/address')->load($row->vid);
			
			return ($contact ? $contact->getName().($contact->company ? ' ['.$contact->company.']' : '') : '[Deleted]');			
		}
	}
