<?php

class Itech_Attributemanager_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getUsedByCollection($options, $limit){
		$usedByCollection = Mage::getModel('attributemanager/attributemanager')
		->getCollection()
		->addFieldToFilter('option_id', array('in' => $options))
		->setPageSize($limit);
		return $usedByCollection;
	}
	
	public function getAttributeOptionValue($arg_attribute, $arg_value) {
		$attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $arg_attribute);
		$options = array();
		if ($attribute->usesSource()) {
			$options = $attribute->getSource()->getAllOptions(false);
			foreach($options as $option){
				if($option['value']==$arg_value){
					return $option['label'];
				}
			}
		}
		
		return false;
	}

}