<?php
/**
 * @category   local
 * @package    SavantDegrees
 * @copyright  Copyright (c) 2009 Savant Degrees (http://www.savantdegrees.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Slink_MageSaasu_Block_Admin_Sales_View_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
		$form = new Varien_Data_Form(array(
										   'id' => 'edit_form',
										   'action' => $this->getUrl('*/*/list', array('id'=>$this->getRequest()->getParam('id'))),
										   'method' => 'post'
										   ));	
      
		$fieldset = $form->addFieldset('view_link', array('legend' => Mage::helper('slink')->__('Link Details')));
		

        $fieldset->addField('invoice_number', 'text', array(	'name'      => 'invoice_number',
												'title'     => Mage::helper('slink')->__('Saasu Invoice No.'),
												'label'     => Mage::helper('slink')->__('Saasu Invoice No.'),
												'maxlength' => '50',
												'readonly'  => true,
												));
		$fieldset->addField('tags', 'text', array(          'name'=>'tags',
                                                            'title'=>Mage::helper('slink')->__('Tags'),
                                                            'label'=>Mage::helper('slink')->__('Tags'),
                                                            'readonly'=>'true'));
    
		$fieldset->addField('transferred', 'text', array(	'name'=>'transferred',
                                                            'title'=>Mage::helper('slink')->__('Transferred at'),
                                                            'label'=>Mage::helper('slink')->__('Transferred at'),
                                                            'format'    => 'Y-M-d H:m:s',
                                                            'readonly'=>'true'));

		
		$invoice_details = $form->addFieldset('view_invoice', array('legend' => Mage::helper('slink')->__('Invoice Details')));
		
        $invoice_details->addField('increment_id', 'text', array(	'name'      => 'increment_id',
												'title'     => Mage::helper('slink')->__('Magento Order ID'),
												'label'     => Mage::helper('slink')->__('Magento Order ID'),
												'maxlength' => '50',
												'readonly'  => true,
												));		
		$invoice_details->addField('store_name', 'text', array('name'=>'store_name',
														  'title'=>Mage::helper('slink')->__('Store'),
														  'label'=>Mage::helper('slink')->__('Store'),
														  'readonly'=>'true'));							
		
		$invoice_details->addField('billing_name', 'text', array('name'=>'billing_name',
													  'title'=>Mage::helper('slink')->__('Billing Name'),
													  'label'=>Mage::helper('slink')->__('Billing Name'),
													  'readonly'=>'true'));							
		
		$invoice_details->addField('shipping_name', 'text', array('name'=>'shipping_name',
														  'title'=>Mage::helper('slink')->__('Shipping Name'),
														  'label'=>Mage::helper('slink')->__('Shipping Name'),
														  'readonly'=>'true'));							
		
		$invoice_details->addField('grand_total', 'text', array('name'=>'grand_total',
														'title'=>Mage::helper('slink')->__('Total Value'),
														'label'=>Mage::helper('slink')->__('Total Value'),
														'readonly'=>'true'));					

		$invoice_details->addField('order_currency_code', 'text', array('name'=>'order_currency_code',
																'title'=>Mage::helper('slink')->__('Currency'),
																'label'=>Mage::helper('slink')->__('Currency'),
																'readonly'=>'true'));					
		
		$invoice_details->addField('coupon_code', 'text', array('name'=>'coupon_code',
																'title'=>Mage::helper('slink')->__('Coupon Code'),
																'label'=>Mage::helper('slink')->__('Coupon Code'),
																'readonly'=>'true'));					
		
		$invoice_details->addField('updated_at', 'date', array(	'name'=>'updated_at',
															   'title'=>Mage::helper('slink')->__('Updated at'),
															   'label'=>Mage::helper('slink')->__('Updated at'),
															   'format'=>'Y-M-d h:m:s',
															   'readonly'=>'true'));									
		$invoice_details->addField('created_at', 'date', array(	'name'=>'created_at',
															'title'=>Mage::helper('slink')->__('Created at'),
															'label'=>Mage::helper('slink')->__('Created at'),
															'format'=>'Y-M-d h:m:s',
															'readonly'=>'true'));							
		
		
		$values = Mage::registry('current_sale')->getData();				

		$invoice_number = Mage::registry('invoice_number');
		$billing = Mage::registry('billing');
		$shipping = Mage::registry('shipping');		
		
		$values['id'] = Mage::registry('slink_id');
		$values['transferred'] = Mage::registry('transferred');
		$values['invoice_number'] = Mage::registry('invoice_number');
		$values['billing_name'] = $billing['lastname'].', '.$billing['firstname'];
		$values['shipping_name'] = $shipping['lastname'].', '.$shipping['firstname'];
		$values['tags'] = Mage::registry('sale_tags');
		
		$form->setValues($values);
        $form->setUseContainer(true);		
        $this->setForm($form);	
		
        return parent::_prepareForm();  
    }
	
}