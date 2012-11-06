<?php

class Itech_Attributemanager_Model_Mysql4_Attributemanager_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('attributemanager/attributemanager');
    }
}