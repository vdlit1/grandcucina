<?php

	class Slink_MageSaasu_Block_Admin_Sales_List extends Mage_Adminhtml_Block_Widget_Grid_Container
	{
		public function __construct()
		{

			parent::__construct();

			$this->_blockGroup = 'slink';
			$this->_controller = 'admin_sales_list';
			
			$this->_headerText = Mage::helper('slink')->__('Link Sale(s)');
			$this->_removeButton('add');

			
		}

	}
