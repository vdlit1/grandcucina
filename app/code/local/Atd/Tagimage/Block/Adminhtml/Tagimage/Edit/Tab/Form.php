<?php

class Atd_Tagimage_Block_Adminhtml_Tagimage_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('tagimage_form', array('legend'=>Mage::helper('tagimage')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('tagimage')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

    
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('tagimage')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('tagimage')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('tagimage')->__('Disabled'),
              ),
          ),
      ));
     
  
     
      if ( Mage::getSingleton('adminhtml/session')->getTagimageData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getTagimageData());
          Mage::getSingleton('adminhtml/session')->setTagimageData(null);
      } elseif ( Mage::registry('tagimage_data') ) {
          $form->setValues(Mage::registry('tagimage_data')->getData());
      }
      return parent::_prepareForm();
  }
}