<?php

class Atd_Tagimage_Model_Mysql4_Tagimage_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('tagimage/tagimage');
    }
     public function addCategoryFilter($ids) {
        $this->getSelect()
                ->where('main_table.tagimage_id in (?)',  $ids);
        return $this;
    }
 
}