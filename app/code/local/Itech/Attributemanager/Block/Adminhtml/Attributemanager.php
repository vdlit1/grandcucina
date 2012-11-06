<?php
class Itech_Attributemanager_Block_Adminhtml_Attributemanager extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_attributemanager';
    $this->_blockGroup = 'attributemanager';
    $this->_headerText = Mage::helper('attributemanager')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('attributemanager')->__('Add Item');
    parent::__construct();
  }
}