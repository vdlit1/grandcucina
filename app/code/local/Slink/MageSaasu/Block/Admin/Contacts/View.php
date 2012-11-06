<?php

	class Slink_MageSaasu_Block_Admin_Contacts_View extends Mage_Adminhtml_Block_Widget_Form_Container
	{
		public function __construct()
		{	

			parent::__construct();
			
			$this->_blockGroup = 'slink';
			$this->_mode = 'view';
			$this->_controller = 'admin_contacts';
		
			if( $this->getRequest()->getParam($this->_objectId) ) {

				$contact =	Mage::getModel('slink/contacts')->load($this->getRequest()->getParam($this->_objectId));
										
				$contactinfo =	Mage::getModel('customer/address')->load($contact->getData('vid'));
				$customer = Mage::getModel('customer/customer')->load($contactinfo->parent_id);
				
				if($contactinfo->entity_id == $customer->default_billing){
					$address_type='Billing';
				}elseif($contactinfo->entity_id == $customer->default_shipping){
					$address_type='Shipping';					
				}else{
					$address_type='Additional';					
				}
			
                $contact->setData('transferred', Mage::getModel('core/date')->date('Y-m-d H:i:s', $contact->getData('transferred')));
                                  
				Mage::register('current_contact', array_merge($contact->getData(), 
															  ($contactinfo ? $contactinfo->getData(): ''),
															  array('customer_id'=>$customer->entity_id),
															  array('address_type'=>$address_type))); 

			}
			
			$this->_removeButton('save');
			$this->_removeButton('delete');			
			$this->_removeButton('reset');						
			
/*			//Still buggy
			$this->_addButton('link', array('label'=>'Link', 
											'style'=>'width:70px',
											'class'=>'link',
											'onclick'	=> 'editForm.submit();',
											), -1);			*/
		}			
			
		
		public function getHeaderText()
		{
			return Mage::helper('slink')->__("View Customer");			
		}
	}
