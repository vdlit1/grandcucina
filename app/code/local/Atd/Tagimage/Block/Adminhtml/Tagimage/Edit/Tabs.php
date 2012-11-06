<?php

class Atd_Tagimage_Block_Adminhtml_Tagimage_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('tagimage_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('tagimage')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
       
        $this->addTab('images_section', array(
          'label'     => Mage::helper('tagimage')->__('Upload images'),
          'title'     => Mage::helper('tagimage')->__('Upload images'),
          'content'   => $this->getLayout()->createBlock('tagimage/adminhtml_tagimage_edit_tab_images')->toHtml(),
      ));

        $this->addTab('category_section', array(
          'label'     => Mage::helper('tagimage')->__('Choose category'),
          'title'     => Mage::helper('tagimage')->__('Choose category'),
          'content'   => $this->getLayout()->createBlock('tagimage/adminhtml_tagimage_edit_tab_category')->toHtml(),
      ));

        $this->addTab('content_section', array(
          'label'     => Mage::helper('tagimage')->__('Image information'),
          'title'     => Mage::helper('tagimage')->__('Image information'),
          'content'   => $this->getLayout()->createBlock('tagimage/adminhtml_tagimage_edit_tab_content')->toHtml(),
      ));

      return parent::_beforeToHtml();
  }
}