<?php

class Atd_Tagimage_Model_Config_Source_Page
{

    public function toOptionArray()
    {
        $_collection = Mage::getSingleton('cms/page')->getCollection()
                ->addFieldToFilter('is_active', 1);
        $_result = array();
        foreach ($_collection as $item) {
               $selected =false;
               if(in_array($item->getData('page_id'),$this->getSelected())){
                    $selected = true;
               }
                $data = array(
                    'selected'=> $selected,
                    'value' => $item->getData('page_id'),
                    'label' => $item->getData('title'));
                $_result[] = $data;
           
        }
        return $_result;
    }




    public function getSelected(){
        $id= Mage::app()->getRequest()->getParam('id');
        $page = Mage::getModel('tagimage/page')->load($id);
        if($page->getId()){
            $option = array();
            foreach(explode(',',$page->getPage()) as $p){
                $option[] = $p;
            }
            return  $option;
        }else{
            return array();
        }
    }
  }
