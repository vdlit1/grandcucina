<?php

class Itech_Managebrands_Block_Adminhtml_Managebrands_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('managebrands_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('managebrands')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('managebrands')->__('Item Information'),
          'title'     => Mage::helper('managebrands')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('managebrands/adminhtml_managebrands_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}