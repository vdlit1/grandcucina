<?php

class Itech_Managebrands_Block_Adminhtml_Managebrands_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'managebrands';
        $this->_controller = 'adminhtml_managebrands';
        
        $this->_updateButton('save', 'label', Mage::helper('managebrands')->__('Save Brand'));
        $this->_updateButton('delete', 'label', Mage::helper('managebrands')->__('Delete'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('managebrands_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'managebrands_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'managebrands_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('managebrands_data') && Mage::registry('managebrands_data')->getId() ) {
            return Mage::helper('managebrands')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('managebrands_data')->getTitle()));
        } else {
            return Mage::helper('managebrands')->__('Add Item');
        }
    }
}