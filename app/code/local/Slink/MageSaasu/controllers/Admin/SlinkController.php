<?php
/**
 * Slink for Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * @category   Slink_MageSaasu
 * @package    
 * @copyright  Copyright (c) 2009 Bernard Tai
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Bernard Tai bernard.tai@saaslink.net
 */
	
class Slink_MageSaasu_Admin_SlinkController extends Mage_Adminhtml_Controller_Action
{
	protected function indexAction() {
		$model = Mage::getSingleton('slink/slink'); 
		
		$this->loadLayout();
		$this->_addContent($this->getLayout()->createBlock('slink/admin_slink'));
        $this->getLayout()->getBlock('head')->setTitle('About Slink by Saaslink');        
		$this->renderLayout();

		return $this; 
	}   
}