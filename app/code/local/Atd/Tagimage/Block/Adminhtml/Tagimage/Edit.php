<?php

class Atd_Tagimage_Block_Adminhtml_Tagimage_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'tagimage';
        $this->_controller = 'adminhtml_tagimage';
        
        $this->_updateButton('save', 'label', Mage::helper('tagimage')->__('Save Images'));
        $this->_updateButton('delete', 'label', Mage::helper('tagimage')->__('Delete Images'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('tagimage_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'tagimage_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'tagimage_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('tagimage_data') && Mage::registry('tagimage_data')->getId() ) {
            return Mage::helper('tagimage')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('tagimage_data')->getTitle()));
        } else {
            return Mage::helper('tagimage')->__('Add Images');
        }
    }
}