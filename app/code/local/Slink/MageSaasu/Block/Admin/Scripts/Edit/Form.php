<?php
/**
 * @category   local
 * @package    SavantDegrees
 * @copyright  Copyright (c) 2009 Savant Degrees (http://www.savantdegrees.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Slink_MageSaasu_Block_Admin_Scripts_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {

		$form = new Varien_Data_Form(array(
				'id' => 'edit_form',
				'action' => $this->getUrl('*/*/save', array('id'=> $this->getRequest()->getParam('id'))),
				'method' => 'post',
				'enctype'=>'multipart/form-data'
				));
			
        $fieldset = $form->addFieldset('edit_script', array('legend' => Mage::helper('slink')->__('Script Details')));
		
        if($this->getRequest()->getParam('id')>0){
			$fieldset->addField('file', 'text', array('name'      => 'file',
													  'title'     => Mage::helper('slink')->__('File'),
													  'label'     => Mage::helper('slink')->__('File'),
													  'required'  => true,													  
													  'readonly'  => true,													  
														));
			$fieldset->addField('published', 'select', array('name'	=>	'published',
													   'title' =>	Mage::helper('slink')->__('Published'),
													   'label' =>	Mage::helper('slink')->__('Published'),													 
															 'options'=> array('0'=>Mage::helper('slink')->__('No'),
																			   '1'=>Mage::helper('slink')->__('Yes'))
														 ));
			
			$fieldset->addField('title', 'text', array('name'	=>	'title',
													   'title' =>	Mage::helper('slink')->__('Title'),
													   'label' =>	Mage::helper('slink')->__('Title'),													 
													   'readonly'=>	true));
			
			$fieldset->addField('description', 'textarea', array('name'	=>	'description',
																 'title' =>	Mage::helper('slink')->__('Description'),
																 'label' =>	Mage::helper('slink')->__('Description'),													 
																 'readonly'=>	true));
			
			
			$fieldset->addField('version', 'text', array('name'		=>	'version',
														 'title'	=>	Mage::helper('slink')->__('Version'),
														 'label'	=>	Mage::helper('slink')->__('Version'),													 
														 'style'	=> 'width:50px',
														 'readonly'	=>	true));
			$fieldset->addField('created', 'date', array('name'		=>	'version',
														 'title'	=>	Mage::helper('slink')->__('Created at'),
														 'label'	=>	Mage::helper('slink')->__('Created at'),													 
														 'format'	=>'Y-M-d h:m:s',
														 'readonly'	=>	true));			
			$values = Mage::registry('current_script')->getData();
			$form->setValues($values);			
			
		}else{
			$fieldset->addField('file', 'file', array('name'      => 'file',
													  'title'     => Mage::helper('slink')->__('File'),
													  'label'     => Mage::helper('slink')->__('File'),
													  'required'  => true,
													  'readonly'  => true,
													  ));				
		}
	

		$form->setUseContainer(true);		
        $this->setForm($form);
        return parent::_prepareForm();
    }
	
}