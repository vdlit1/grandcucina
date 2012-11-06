<?php
/**
 * @category   local
 * @package    Slink
 * @copyright  Copyright (c) 2009 Slink 
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Slink_MageSaasu_Block_Admin_Items_View_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
		$form = new Varien_Data_Form(array(
				'id' => 'view_form',
				'action' => $this->getUrl('*/*/list', array('id'=>'')),
				'method' => 'post'
				));
			
        $fieldset = $form->addFieldset('view_link', array('legend' => Mage::helper('slink')->__('Link Details')));

		$fieldset->addField('entity_uid', 'text', array(
												'name'      => 'uid',
												'title'     => Mage::helper('slink')->__('Saasu ID'),
												'label'     => Mage::helper('slink')->__('Saasu ID'),
												'maxlength' => '50',
												'readonly'  => true
												));		
		
		$fieldset->addField('transferred', 'date', array(	'name'      => 'transferred',
																'title'     => Mage::helper('slink')->__('Transferred at'),
																'label'     => Mage::helper('slink')->__('Transferred at'),
																'maxlength' => '50',
                                                                'format'    => 'Y-M-d H:m:s',
																'readonly'  => true
															));				
        $item = $form->addFieldset('view_item', array('legend' => Mage::helper('slink')->__('Item Details')));		
        
		$item->addField('entity_id', 'text', array(
													'name'      => 'entity_id',
													'title'     => Mage::helper('slink')->__('Item ID'),
													'label'     => Mage::helper('slink')->__('Item ID'),
													'maxlength' => '50',
													'readonly'  => true
													));		
		$item->addField('type_id', 'text', array(
											 'name'      => 'type_id',
											 'title'     => Mage::helper('slink')->__('Product Type'),
											 'label'     => Mage::helper('slink')->__('Product Type'),
											 'maxlength' => '50',
											'readonly'  => true
											 ));
		$item->addField('sku', 'text', array(
												  'name'      => 'sku',
												  'title'     => Mage::helper('slink')->__('Item Code'),
												  'label'     => Mage::helper('slink')->__('Item Code'),
												  'maxlength' => '50',
													'readonly'  => true
												  ));
		
        $item->addField('name', 'text', array(
												  'name'      => 'productname',
												  'title'     => Mage::helper('slink')->__('Name'),
												  'label'     => Mage::helper('slink')->__('Name'),
													'readonly'  => true	,										  
												  'maxlength' => '255',
												  ));
        $item->addField('price', 'text', array(
														  'name'      => 'price',
														  'title'     => Mage::helper('slink')->__('Price'),
														  'label'     => Mage::helper('slink')->__('Price'),
													'readonly'  => true	,										   
														  'maxlength' => '255',
														  ));		
		$item->addField('created_at', 'date', array('name'=>'created_at',
														'title'=>Mage::helper('slink')->__('Created at'),
														'label'=>Mage::helper('slink')->__('Created at'),
														'format'=>'d-m-Y',
														'readonly'=>'true'));
		$item->addField('updated_at', 'date', array('name'=>'updated_at',
														'title'=>Mage::helper('slink')->__('Updated at'),
														'label'=>Mage::helper('slink')->__('Updated at'),
														'format'=>'d-m-Y',														
														'readonly'=>'true'));		
		
		
		$inventory = $form->addFieldset('view_stock', array('legend' => Mage::helper('slink')->__('Stock Details')));		

		$inventory->addField('qty', 'text', array(
														  'name'      => 'qty',
														  'title'     => Mage::helper('slink')->__('Qty in Stock'),
														  'label'     => Mage::helper('slink')->__('Qty in Stock'),
														  'maxlength' => '50',
														  'required'  => true,
														  ));
        
		$inventory->addField('min_qty', 'text', array(
												 'name'      => 'min_qty',
												 'title'     => Mage::helper('slink')->__('Minimum Qty'),
												 'label'     => Mage::helper('slink')->__('Minimum Qty'),
												 'maxlength' => '50',
												 'required'  => true,
												 ));
		$inventory->addField('backorders', 'text', array(
														  'name'      => 'backorders',
														  'title'     => Mage::helper('slink')->__('Backorders'),
														  'label'     => Mage::helper('slink')->__('Backorders'),
														  'maxlength' => '50',
														  'required'  => true,
														  ));		

		
        $form->setUseContainer(true);		
		$form->setValues(Mage::registry('current_item'));
		
        $this->setForm($form);
        return parent::_prepareForm();
    }
	
}