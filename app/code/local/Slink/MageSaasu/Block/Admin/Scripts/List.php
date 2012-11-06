<?php

	class Slink_MageSaasu_Block_Admin_Scripts_List extends Mage_Adminhtml_Block_Widget_Grid_Container
	{
		public function __construct()
		{

			$this->_addButtonLabel = Mage::helper('slink')->__('Add New Script');

			parent::__construct();

			$this->_blockGroup = 'slink';
			$this->_controller = 'admin_scripts_list';

			$this->_headerText = Mage::helper('slink')->__('Script(s)');
			
		}

	}
