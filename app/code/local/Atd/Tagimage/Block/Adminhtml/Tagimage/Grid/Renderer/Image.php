<?php

class Atd_Tagimage_Block_Adminhtml_Tagimage_Grid_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action {
    public function render(Varien_Object $row)
    {
        return $this->_getValue($row);
    }

       public function _getValue(Varien_Object $row)
    {
        if ($getter = $this->getColumn()->getGetter()) {
            $val = $row->$getter();
        }
        $val = $row->getData($this->getColumn()->getIndex());
        $val = str_replace("no_selection", "", $val);
        $url = Mage::getBaseUrl('media') . DS .'tagimage'. $val;

        $out='';
        $out .= "<center><img src=". $url ." width='60px' style='border:5px solid ghostWhite'/><center>" ;
        return $out;

    }
}