<?php
/**
 * AHT_Optionimages Extension
 *
 * @category    Local
 * @package     AHT_Optionimages
 * @author      dungnv (dungnv@arrowhitech.com)
 * @copyright   Copyright(c) 2011 Arrowhitech Inc. (http://www.arrowhitech.com)
 *
 */

/**
 *
 * @category   Local
 * @package    AHT_Optionimages
 * @author     dungnv <dungnv@arrowhitech.com>
 */
class AHT_Optionimages_Block_Attribute extends Mage_Core_Block_Template
{
    protected $_attributeCode   = NULL;

    public function getFileNameImageImages($optionId)
    {
        return Mage::getModel('optionimages/images')->getFileNameImageImages($optionId);
    }
	
	public function getFileName2ImageImages($optionId)
    {
        return Mage::getModel('optionimages/images')->getFileName2ImageImages($optionId);
    }

    public function setAttributeCode($varName)
    {
        $this->_attributeCode = $varName;
        return $this;
    }

    public function getAttributeCode()
    {
        if(!$this->_attributeCode)
            $this->_attributeCode = $this->_getData('attribute_code');
        return $this->_attributeCode;
    }

    public function getAllOptionValue()
    {
        $storeId = Mage::app()->getStore()->getId();
        $attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')
                            ->setCodeFilter($this->getAttributeCode())
                            ->getFirstItem();

        $optionCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                        ->setAttributeFilter($attributeInfo->getAttributeId())
                        ->setStoreFilter($storeId)
                        ->setPositionOrder('desc', true)->load();

        $data = array();
        $i = 0;
        foreach ($optionCollection as $option) {
            $data[$i]['value'] = $option->getValue();
            $data[$i]['sort_order'] = $option->getSortOrder();
            $data[$i]['filename'] = $this->getFileNameImageImages($option->getOptionId());
			$data[$i]['filename2'] = $this->getFileName2ImageImages($option->getOptionId());
            $i++;
        }
        return $data;
    }
}
