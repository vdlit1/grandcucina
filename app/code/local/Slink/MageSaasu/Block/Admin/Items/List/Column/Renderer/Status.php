<?php
	class Slink_MageSaasu_Block_Admin_Items_List_Column_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
	{
		public function render(Varien_Object $row){								
			return '<img width="16px" '.(strtotime($row->getData('transferred')) > strtotime($row->getData('updated_at')) ? 'src="'.$this->getSkinUrl('images/success_msg_icon.gif').'" alt="LINKED" ':
											  'src="'.$this->getSkinUrl('images/error_msg_icon.gif').'" alt=""') .'>';						 
			
		}
	}
