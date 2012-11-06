<?php
	class Slink_MageSaasu_Block_Admin_Schedules_List_Column_Renderer_Email extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
	{
		public function render(Varien_Object $row){
			$data = $this->_getValue($row);
			return ($data <> "" ? 'Yes' : 'No');
		}
	}
