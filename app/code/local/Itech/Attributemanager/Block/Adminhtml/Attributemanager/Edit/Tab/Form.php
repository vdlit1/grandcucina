<?php

class Itech_Attributemanager_Block_Adminhtml_Attributemanager_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('attributemanager_form', array('legend'=>Mage::helper('attributemanager')->__('Item information')));
     /*
	  $fieldset->addField('attribute_code', 'select', array(
	  	  'name' => 'attribute_code',
		  'values' => array('manufacturer'=>'Branch')
	  ));
	  */
	  $resource    = Mage::getSingleton('core/resource'); 
	  $read        = $resource->getConnection('catalog_read'); 
	  $ev  		   = $resource->getTableName('eav_attribute_option_value');
	  $ea  		   = $resource->getTableName('eav_attribute');
	  $eo  		   = $resource->getTableName('eav_attribute_option');
	  $select 	   = 'SELECT DISTINCT ev.option_id, value FROM eav_attribute_option_value AS ev, eav_attribute AS ea, eav_attribute_option AS eo WHERE (ea.attribute_id = eo.attribute_id) AND (eo.option_id = ev.option_id) AND (ea.attribute_code = "used_by")';
	  $res               = $read->fetchAll($select);
	  
	  $arr_manu = array();
	  foreach ($res as $item) {
			$mid    = $item['option_id'];
			$mname = $item['value'];
			$arr_manu[] = array (
				'value' => $mid,
				'label' => $mname
			);
	  }
	  
      $fieldset->addField('option_id', 'select', array(
          'label'     => Mage::helper('attributemanager')->__('Used by'),
          'name'      => 'option_id',
		  'values'    => $arr_manu
      ));
	  
      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('attributemanager')->__('Image'),
          'required'  => false,
          'name'      => 'filename',
	  ));
	  
	  $fieldset->addField('url', 'text', array(
          'label'     => Mage::helper('attributemanager')->__('Website'),
          'required'  => false,
          'name'      => 'url',
	  ));
	  
	  $fieldset->addField('attribute_decription', 'editor', array(
          'name'      => 'attribute_decription',
          'label'     => Mage::helper('attributemanager')->__('Location'),
          'title'     => Mage::helper('attributemanager')->__('Location'),
          'style'     => 'width:500px; height:300px;',
          'wysiwyg'   => false,
      ));
	  

      if ( Mage::getSingleton('adminhtml/session')->getAttributemanagerData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getAttributemanagerData());
          Mage::getSingleton('adminhtml/session')->setAttributemanagerData(null);
      } elseif ( Mage::registry('attributemanager_data') ) {
          $form->setValues(Mage::registry('attributemanager_data')->getData());
      }
      return parent::_prepareForm();
  }
}