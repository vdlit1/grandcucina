<?php

class Itech_Managebrands_Block_Adminhtml_Managebrands_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected $_options;
	
	public function toOptionArray()
    {
        if(!$this -> _options)
        {
            $this    -> _options = array(
                array(
                    'value'    => 0,
                    'label'    => Mage::helper('catalog') -> __('Please select static block ...'),
                )
            );

            $options = Mage::getResourceModel('cms/block_collection') -> load() -> toOptionArray();
            $this -> _options = array_merge($this -> _options, $options);
        }

        return $this->_options;
    }

  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('managebrands_form', array('legend'=>Mage::helper('managebrands')->__('Item information')));
     
	  $resource    = Mage::getSingleton('core/resource'); 
	  $read        = $resource->getConnection('catalog_read'); 
	  $ev  		   = $resource->getTableName('eav_attribute_option_value');
	  $ea  		   = $resource->getTableName('eav_attribute');
	  $eo  		   = $resource->getTableName('eav_attribute_option');
	  $select 	   = 'SELECT DISTINCT ev.option_id, value FROM eav_attribute_option_value AS ev, eav_attribute AS ea, eav_attribute_option AS eo WHERE (ea.attribute_id = eo.attribute_id) AND (eo.option_id = ev.option_id) AND (ea.attribute_code = "grandcucina_brands")';
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
          'label'     => Mage::helper('attributemanager')->__('Brand'),
          'name'      => 'option_id',
		  'values'    => $arr_manu
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('managebrands')->__('Logo'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
     
      $fieldset->addField('description', 'editor', array(
          'name'      => 'description',
          'label'     => Mage::helper('managebrands')->__('Description'),
          'title'     => Mage::helper('managebrands')->__('Description'),
          'style'     => 'width:600px; height:150px;',
          'wysiwyg'   => false,
      ));
	  
	  $fieldset->addField('static1', 'select', array(
          'label'     => Mage::helper('attributemanager')->__('Right Columb Static 1'),
          'name'      => 'static1',
		  'values'    => $this->toOptionArray()
      ));
	  
	  $fieldset->addField('static2', 'select', array(
          'label'     => Mage::helper('attributemanager')->__('Right Columb Static 2'),
          'name'      => 'static2',
		  'values'    => $this->toOptionArray()
      ));
	  
	  $fieldset->addField('static3', 'select', array(
          'label'     => Mage::helper('attributemanager')->__('Right Columb Static 3'),
          'name'      => 'static3',
		  'values'    => $this->toOptionArray()
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getManagebrandsData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getManagebrandsData());
          Mage::getSingleton('adminhtml/session')->setManagebrandsData(null);
      } elseif ( Mage::registry('managebrands_data') ) {
          $form->setValues(Mage::registry('managebrands_data')->getData());
      }
      return parent::_prepareForm();
  }
}