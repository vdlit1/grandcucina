<?php

	class Slink_MageSaasu_Block_Admin_Slink extends Mage_Adminhtml_Block_Widget

	{
		public function __construct(){
			$this->_addButtonLabel = Mage::helper('slink')->__('About Slink');
			parent::__construct();
			
			$this->_blockGroup = 'slink';
			$this->_controller = 'admin_slink';
			$this->_headerText = Mage::helper('slink')->__('About Slink');
	}
		protected function _toHtml(){
			return  '<h1>About Slink</h1>'.
                    "Version:\t\t<strong>".
                    (string) Mage::getConfig()->getNode()->modules->Slink_MageSaasu->version.
                    "</strong><br /><br />".
                    'Slink is designed to transfer sales other important data to the Saasu.com online accounting system.<br /> '.
                    'Visit <a target="blank" href="http://wiki.saaslink.net">wiki.saaslink.net</a> for more information.';
		}
	}
