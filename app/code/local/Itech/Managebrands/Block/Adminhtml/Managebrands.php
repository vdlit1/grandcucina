<?php
class Itech_Managebrands_Block_Adminhtml_Managebrands extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_managebrands';
    $this->_blockGroup = 'managebrands';
    $this->_headerText = Mage::helper('managebrands')->__('Brands');
    $this->_addButtonLabel = Mage::helper('managebrands')->__('Add Brand Information');
    parent::__construct();
  }
}