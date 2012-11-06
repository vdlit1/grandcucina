<?php

	class Slink_MageSaasu_Block_Admin_Contacts_List extends Mage_Adminhtml_Block_Widget_Grid_Container
	{
		public function __construct()
		{

			parent::__construct();

			$this->_blockGroup = 'slink';
			$this->_controller = 'admin_contacts_list';

			$this->_headerText = Mage::helper('slink')->__('Link Customer(s)');
			$this->_removeButton('add');

		}

	}
