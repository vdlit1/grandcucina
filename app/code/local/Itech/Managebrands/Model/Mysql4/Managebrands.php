<?php

class Itech_Managebrands_Model_Mysql4_Managebrands extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the managebrands_id refers to the key field in your database table.
        $this->_init('managebrands/managebrands', 'managebrands_id');
    }
}