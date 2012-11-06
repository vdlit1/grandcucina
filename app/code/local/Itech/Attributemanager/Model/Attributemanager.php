<?php

class Itech_Attributemanager_Model_Attributemanager extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('attributemanager/attributemanager');
    }
}