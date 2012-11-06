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
	
class Slink_MageSaasu_Model_Config_ScheduleTaskFileOptions implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * Xml config path to value of samplefield1fromgroup1 field from system.xml
     *
     */
    
    /**
     * Fills the select field with values
     * 
     * @return array
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
    	
    	/**
    	 * 
    	 * If we were to choose "Cool custom value 4" from admin backend then 
    	 * var_dump would result in "3" which is the value of const OPTION4_VALUE. 
    	 * 
    	 */
    	//var_dump($this->getSomeValueFromSystemConfigFile());
    	

    }
}
