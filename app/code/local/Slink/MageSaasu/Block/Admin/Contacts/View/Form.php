<?php
/**
 * @category   local
 * @package    SavantDegrees
 * @copyright  Copyright (c) 2009 Savant Degrees (http://www.savantdegrees.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Slink_MageSaasu_Block_Admin_Contacts_View_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
		$form = new Varien_Data_Form(array(
				'id' => 'view_form',
				'action' => $this->getUrl('*/*/list', array('id'=>'')),
				'method' => 'post'
				));
			
        $fieldset = $form->addFieldset('view_link', array('legend' => Mage::helper('slink')->__('Link Details')));
		
		$fieldset->addField('transferred', 'date', array('name'		=> 'transferred',
                                                         'title'	=> Mage::helper('slink')->__('Transferred at'),
                                                         'label'	=> Mage::helper('slink')->__('Transferred at'),
                                                         'width'	=> '255',
                                                         'format'   =>'Y-M-d H:m:s',
                                                         'readonly'	=> 'true',
														 ));
		
        $contact = $form->addFieldset('view_contact', array('legend' => Mage::helper('slink')->__('Customer Details')));

		$contact->addField('customer_id', 'text', array('name'      => 'customer_id',
														'title'     => Mage::helper('slink')->__('Customer ID'),
														'label'     => Mage::helper('slink')->__('Customer ID'),
														'maxlength' => '50',
														'readonly'=>'true'												
														));		
		
		$contact->addField('entity_id', 'text', array(	'name'      => 'entity_id',
                                                        'title'     => Mage::helper('slink')->__('Address ID'),
                                                        'label'     => Mage::helper('slink')->__('Address ID'),
                                                        'maxlength' => '50',
                                                        'readonly'=>'true'
                                                        ));

        $contact->addField('address_type', 'text', array('name'      => 'address_type',
                                                         'title'     => Mage::helper('slink')->__('Address Type'),
                                                         'label'     => Mage::helper('slink')->__('Address Type'),
                                                         'readonly'=>'true',
                                                         ));
        $contact->addField('lastname', 'text', array(   'name'      => 'lastname',
                                                        'title'     => Mage::helper('slink')->__('Last Name'),
                                                        'label'     => Mage::helper('slink')->__('Last Name'),
                                                        'readonly'=>'true',
                                                        ));
        $contact->addField('firstname', 'text', array(	'name'      => 'firstname',
														'title'     => Mage::helper('slink')->__('First Name'),
														'label'     => Mage::helper('slink')->__('First Name'),
														'readonly'=>'true',
														));
        $contact->addField('street', 'text', array(     'name'      => 'street',
                                                        'title'     => Mage::helper('slink')->__('Street'),
                                                        'label'     => Mage::helper('slink')->__('Street'),
                                                        'readonly'=>'true',
                                                        ));
        $contact->addField('city', 'text', array(       'name'      => 'city',
                                                        'title'     => Mage::helper('slink')->__('City'),
                                                        'label'     => Mage::helper('slink')->__('City'),
                                                        'readonly'=>'true',
                                                        ));
        $contact->addField('region', 'text', array(     'name'      => 'region',
                                                        'title'     => Mage::helper('slink')->__('Region'),
                                                        'label'     => Mage::helper('slink')->__('Region'),
                                                        'readonly'=>'true',
                                                        ));
        $contact->addField('postcode', 'text', array(	'name'      => 'postcode',
                                                        'title'     => Mage::helper('slink')->__('Postcode'),
                                                        'label'     => Mage::helper('slink')->__('Postcode'),
                                                        'readonly'=>'true',
                                                        ));
        $contact->addField('country_id', 'text', array( 'name'      => 'country_id',
                                                        'title'     => Mage::helper('slink')->__('Country'),
                                                        'label'     => Mage::helper('slink')->__('Country'),
                                                        'readonly'=>'true',
                                                        ));
		
				
					
        $form->setUseContainer(true);
		$form->setValues(Mage::registry('current_contact'));

		$this->setForm($form);
        return parent::_prepareForm();
    }
	
}