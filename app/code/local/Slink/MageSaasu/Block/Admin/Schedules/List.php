<?php

	class Slink_MageSaasu_Block_Admin_Schedules_List extends Mage_Adminhtml_Block_Widget_Grid_Container
	{
		public function __construct()
		{

			$this->_addButtonLabel = Mage::helper('slink')->__('Add New Schedule');

			parent::__construct();

			$this->_blockGroup = 'slink';
			$this->_controller = 'admin_schedules_list';

			$this->_headerText = Mage::helper('slink')->__('Schedule(s)');
			
		}

	}
