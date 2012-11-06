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
class AHT_Optionimages_Model_Mysql4_Images extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        // Note that the value_id refers to the key field in your database table.
        $this->_init('optionimages/images', 'value_id');
    }

    public function getFileNameImageImages($optionId)
    {
        $read = $this->_getReadAdapter();
        $select = $read->select()
              ->from(array('filename'=>$this->getTable('aht_attribute_option_value')),array('filename'))
              ->where('option_id=?', $optionId);
        return $read->fetchOne($select);
    }
	
	public function getFileName2ImageImages($optionId)
    {
        $read = $this->_getReadAdapter();
        $select = $read->select()
              ->from(array('filename2'=>$this->getTable('aht_attribute_option_value')),array('filename2'))
              ->where('option_id=?', $optionId);
        return $read->fetchOne($select);
    }
}
