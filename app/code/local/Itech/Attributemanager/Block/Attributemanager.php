<?php
class Itech_Attributemanager_Block_Attributemanager extends Mage_Core_Block_Template
{
	public function getBrandCollection(){
		return Mage::getModel('attributemanager/attributemanager')->getCollection();
	}
	
	public function getLabelOfOption($optionId){
		$_product = Mage::getModel('catalog/product');
		$_attributes = Mage::getResourceModel('eav/entity_attribute_collection')
				->setEntityTypeFilter($_product->getResource()->getTypeId())
				->addFieldToFilter('attribute_code', 'manufacturer');
		
		$_attribute = $_attributes->getFirstItem()->setEntity($_product->getResource());
		$manufacturers = $_attribute->getSource()->getAllOptions(false);
		
		foreach($manufacturers as $option){
			if($option['value']==$optionId){
				return $option['label'];
			}
		}
	}
}