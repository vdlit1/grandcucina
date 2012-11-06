<?php

class Itech_Attributemanager_Block_Adminhtml_Attributemanager_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'attributemanager';
        $this->_controller = 'adminhtml_attributemanager';
        
        $this->_updateButton('save', 'label', Mage::helper('attributemanager')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('attributemanager')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('attributemanager_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'attributemanager_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'attributemanager_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('attributemanager_data') && Mage::registry('attributemanager_data')->getId() ) {
            return Mage::helper('attributemanager')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('attributemanager_data')->getTitle()));
        } else {
            return Mage::helper('attributemanager')->__('Add Item');
        }
    }
}