<?php
	class Slink_MageSaasu_Block_Admin_Slink_List_Column_Renderer_Date extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
	{
		public function render(Varien_Object $row){
			return ( ($date = $this->_getValue($row)) > 0 ? Mage::getModel('core/date')->date('Y-m-d H:i:s', $date) : '' );
			
		}
	}
