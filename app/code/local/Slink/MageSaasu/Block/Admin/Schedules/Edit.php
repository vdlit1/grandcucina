<?php

	class Slink_MageSaasu_Block_Admin_Schedules_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
	{
		public function __construct()
		{	

			parent::__construct();
			
			$this->_blockGroup = 'slink';
			$this->_mode = 'edit';								
			$this->_controller = 'admin_schedules';

			if( $this->getRequest()->getParam($this->_objectId) ) {
				$schedule = Mage::getModel('slink/schedules')
                ->load($this->getRequest()->getParam($this->_objectId));
                $schedule['last_run'] = Mage::getModel('core/date')->date('Y-m-d H:m:s', $schedule['last_run']);
                $schedule['created_at'] = Mage::getModel('core/date')->date('Y-m-d H:m:s', $schedule['created_at']);
				Mage::register('current_schedule', $schedule);
			}
		}			
			
		
		public function getHeaderText()
		{
			if( $this->getRequest()->getParam($this->_objectId) ) {
				return Mage::helper('slink')->__("Edit Schedule");			
			}else{
				return Mage::helper('slink')->__("Add New Schedule");							
			}
		}
	}
