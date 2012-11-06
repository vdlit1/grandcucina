<?php

class Atd_Tagimage_Model_Status extends Varien_Object
{
    const STATUS_ENABLED	= 0;
    const STATUS_DISABLED      = 1;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('tagimage')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('tagimage')->__('Disabled')
        );
    }
}