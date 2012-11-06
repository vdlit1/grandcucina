<?php
	class Slink_MageSaasu_Block_Admin_Contacts_List_Column_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
	{
		public function render(Varien_Object $row){	
			$contact = Mage::getModel('customer/address')->load($row->vid);
			
			return '<img style="padding-top:2px;" '.(strtotime($row->getData('transferred')) > strtotime($contact->getData('updated_at')) ? 'src="'.$this->getSkinUrl('images/success_msg_icon.gif').'" alt="LINKED" ':
										 'src="'.$this->getSkinUrl('images/error_msg_icon.gif').'" alt=""') .'>';						 
		}
	}
