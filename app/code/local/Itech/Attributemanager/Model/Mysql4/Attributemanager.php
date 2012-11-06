<?php

class Itech_Attributemanager_Model_Mysql4_Attributemanager extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the attributemanager_id refers to the key field in your database table.
        $this->_init('attributemanager/attributemanager', 'attributemanager_id');
    }
}