<?php

class Atd_Quicklook_Model_Mysql4_Quicklook extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the quicklook_id refers to the key field in your database table.
        $this->_init('quicklook/quicklook', 'quicklook_id');
    }
}