<?php

	class Slink_MageSaasu_Block_Admin_Scripts_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
	{
		public function __construct()
		{	

			parent::__construct();
			
			$this->_blockGroup = 'slink';
			$this->_mode = 'edit';								
			$this->_controller = 'admin_scripts';
			
			if( $this->getRequest()->getParam($this->_objectId) ) {
				$script = Mage::getModel('slink/scripts')
                ->load($this->getRequest()->getParam($this->_objectId));
				Mage::register('current_script', $script);
			}
		}			
			
		
		public function getHeaderText()
		{
			if($this->_objectId > 0){
				return Mage::helper('slink')->__("Script Details");			
			}else{
				return Mage::helper('slink')->__("Add New Script");							
			}
		}
	}
