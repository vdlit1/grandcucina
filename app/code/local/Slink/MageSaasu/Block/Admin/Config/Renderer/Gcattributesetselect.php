<?php
	class Slink_MageSaasu_Block_Admin_Config_Renderer_Gcattributesetselect extends Mage_Core_Block_Abstract
	{
		protected function _toHtml(){            
            $column = $this->getColumn();
            
            $sets =  Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter(Mage::getModel('eav/config')->getEntityType('catalog_product')->getId())
            ->load();
        
            $options='';
            foreach($sets as $set){
                $options .= '<option value="'.$set->getData('attribute_set_id').'">'.$set->getData('attribute_set_name').'</option>';
            }
            
            return '<select id="select_#{_id}" class="'.$column['class'].'" style="'.$column['style'].'" name="'.$this->getInputName().'">'.$options.'</select>'.
            '<script type="text\/javascript">var x=document.getElementById("select_#{_id}"); var i; for (i=0;i<x.length;i++){ if(x.options[i].value=="#{'.$this->getColumnName().'}") x.selectedIndex=i; }<\/script>';
 
		}
    }
