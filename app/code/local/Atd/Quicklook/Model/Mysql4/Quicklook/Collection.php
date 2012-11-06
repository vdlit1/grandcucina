<?php

class Atd_Quicklook_Model_Mysql4_Quicklook_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('quicklook/quicklook');
    }
}