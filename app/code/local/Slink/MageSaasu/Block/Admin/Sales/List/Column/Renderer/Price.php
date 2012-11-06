<?php
	class Slink_MageSaasu_Block_Admin_Sales_List_Column_Renderer_Price extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
	{
		public function render(Varien_Object $row){
			return number_format($row->getData('grand_total'), 2);
			
		}
	}
