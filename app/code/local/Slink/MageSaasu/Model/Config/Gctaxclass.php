<?php
/**
 * Slink for Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * @category   Slink_MageSaasu
 * @package    
 * @copyright  Copyright (c) 2009 Bernard Tai
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Bernard Tai bernard.tai@saaslink.net
 */
	
class Slink_MageSaasu_Model_Config_Gctaxclass extends Mage_Core_Model_Config_Data
{
    /**
     * Fills the select field with values
     * 
     * @return array
     */
    public function toOptionArray()
    {
        $classes = Mage::getModel('tax/class')->getCollection();
    	
        $return = array();
        foreach($classes as $class){
            $return[$class->getData('class_id')] = $class->getData('class_name');
        }
        
        return $return;
    }
}
