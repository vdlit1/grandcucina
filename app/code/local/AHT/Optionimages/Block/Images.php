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
class AHT_Optionimages_Block_Images extends Mage_Catalog_Block_Layer_Filter_Attribute
{
    protected function _prepareFilter()
    {
        $this->_filter->setAttributeModel($this->getAttributeModel());
        if(Mage::getStoreConfig('aht_optionimages/general/enabled_images') == 1 && strstr($this->_filter->getRequestVar(),'color')) {
            $this->setTemplate('optionimages/color.phtml');
        }
        return $this;
    }

    public function getFileNameImageImages($optionId)
    {
        return Mage::getModel('optionimages/color')->getFileNameImageImages($optionId);
    }
	
	public function getFileName2ImageImages($optionId)
    {
        return Mage::getModel('optionimages/color')->getFileName2ImageImages($optionId);
    }
}
