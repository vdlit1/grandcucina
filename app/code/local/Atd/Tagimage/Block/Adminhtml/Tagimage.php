<?php
class Atd_Tagimage_Block_Adminhtml_Tagimage extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_tagimage';
    $this->_blockGroup = 'tagimage';
    $this->_headerText = Mage::helper('tagimage')->__('Images Manager');
    $this->_addButtonLabel = Mage::helper('tagimage')->__('Add Images');
    parent::__construct();
  }
}