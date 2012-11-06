<?php
/*
 *  Created on Mar 16, 2011
 *  Author Ivan Proskuryakov - volgodark@gmail.com - Magazento.com
 *  Copyright Proskuryakov Ivan. Magazento.com © 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php

class Magazento_Megamenu_Block_Admin_Category_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {


    protected function _prepareForm() {
        $model = Mage::registry('megamenu_category');
        $form = new Varien_Data_Form(array('id' => 'edit_form_category', 'action' => $this->getData('action'), 'method' => 'post'));
        $form->setHtmlIdPrefix('category_');
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('megamenu')->__('General Information'), 'class' => 'fieldset-wide'));
        if ($model->getCategoryId()) {
            $fieldset->addField('category_id', 'hidden', array(
                'name' => 'category_id',
            ));
        }
		$arrCat = array();
		
		$categories = Mage::getModel('catalog/category')
                    ->getCollection()
                    ->addAttributeToSelect('*')
                    ->addIsActiveFilter()
                    ->addLevelFilter(2)
					->addAttributeToFilter('include_in_menu', 1);
		
		foreach($categories as $cat){
			$mid    = $cat->getId();
			$mname = $cat->getName();
			$arrCat[] = array (
				'value' => $mid,
				'label' => $mname
			);
		}
		

        $fieldset->addField('title', 'text', array(
            'name' => 'title',
            'label' => Mage::helper('megamenu')->__('Title'),
            'title' => Mage::helper('megamenu')->__('Title'),
            'required' => true,
//            'style' => 'width:200px',
        ));

        $fieldset->addField('catalog_id', 'select', array(
            'name' => 'catalog_id',
            'label' => Mage::helper('megamenu')->__('Category'),
            'title' => Mage::helper('megamenu')->__('Category'),
            'required' => true,
			'values'    => $arrCat
        ));

        $fieldset->addField('position', 'select', array(
            'name' => 'position',
            'label' => Mage::helper('megamenu')->__('Position'),
            'title' => Mage::helper('megamenu')->__('Position'),
            'required' => true,
            'options' => Mage::helper('megamenu')->numberArray(20,Mage::helper('megamenu')->__('')),
        ));


        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'stores[]',
                'label' => Mage::helper('megamenu')->__('Store View'),
                'title' => Mage::helper('megamenu')->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            'style' => 'height:150px',
            ));
        } else {
            $fieldset->addField('store_id', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

//        $fieldset->addField('column', 'select', array(
//            'name' => 'column',
//            'label' => Mage::helper('megamenu')->__('Column'),
//            'title' => Mage::helper('megamenu')->__('Column'),
//            'required' => true,
//            'options' =>  Mage::helper('megamenu')->numberArray(5,Mage::helper('megamenu')->__('')),
//        ));

       
        $fieldset->addField('is_active', 'select', array(
            'label' => Mage::helper('megamenu')->__('Status'),
            'title' => Mage::helper('megamenu')->__('Status'),
            'name' => 'is_active',
            'required' => true,
            'options' => array(
                '1' => Mage::helper('megamenu')->__('Enabled'),
                '0' => Mage::helper('megamenu')->__('Disabled'),
            ),
        ));

        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        $fieldset->addField('from_time', 'date', array(
            'name' => 'from_time',
            'time' => true,
            'label' => Mage::helper('megamenu')->__('From Time'),
            'title' => Mage::helper('megamenu')->__('From Time'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format' => $dateFormatIso,
        ));

        $fieldset->addField('to_time', 'date', array(
            'name' => 'to_time',
            'time' => true,
            'label' => Mage::helper('megamenu')->__('To Time'),
            'title' => Mage::helper('megamenu')->__('To Time'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format' => $dateFormatIso,
        ));

//        print_r($model->getData());
//        exit();
//        $form->setUseContainer(true);
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
