<?php
	class Slink_MageSaasu_Block_Admin_Items_List_Column_Renderer_Name extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
	{
		public function render(Varien_Object $row){
			$item =  Mage::getModel('catalog/product')->load($row->vid);
			return $item->getData('name');
		}
	}
