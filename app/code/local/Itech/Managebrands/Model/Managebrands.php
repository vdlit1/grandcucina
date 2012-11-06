<?php

class Itech_Managebrands_Model_Managebrands extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('managebrands/managebrands');
    }
}