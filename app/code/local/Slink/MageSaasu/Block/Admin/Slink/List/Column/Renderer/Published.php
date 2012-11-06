<?php
	class Slink_MageSaasu_Block_Admin_Slink_List_Column_Renderer_Published extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
	{
		public function render(Varien_Object $row){
			return '<img style="padding-top:2px" '.(($this->_getValue($row)=='1' || $this->_getValue($row)==true) ? 'src="'.$this->getSkinUrl('images/success_msg_icon.gif').'" alt="YES" ' :   'src="'.
                                                    $this->getSkinUrl('images/error_msg_icon.gif').'" alt="NO" ').'>';
		}
	}
