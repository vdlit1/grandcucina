<?php

class Atd_Tagimage_Model_Mysql4_Page_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('tagimage/page');
    }
     
 
}