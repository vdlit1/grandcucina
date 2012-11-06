<?php

class Atd_Tagimage_Model_Mysql4_Tagimage extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the tagimage_id refers to the key field in your database table.
        $this->_init('tagimage/tagimage', 'tagimage_id');
    }
}