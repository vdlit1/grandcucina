<?php
	class Slink_MageSaasu_Block_Admin_Config_Renderer_Bankaccounts extends Mage_Core_Block_Abstract
	{
		protected function _toHtml(){            
            $column = $this->getColumn();
            
            $accounts = Mage::getModel('slink/bankaccounts')->getCollection();
            foreach($accounts as $account){
                $options .= '<option value="'.$account['entity_uid'].'">'.$account['name']."</option>";
            }
            
            $time = time();
            return '<select id="select_#{_id}" class="'.$column['class'].'" style="'.$column['style'].'" name="'.$this->getInputName().'">'.$options.'</select>'.
                    '<script type="text\/javascript">var x=document.getElementById("select_#{_id}"); var i; for (i=0;i<x.length;i++){ if(x.options[i].value=="#{'.$this->getColumnName().'}") x.selectedIndex=i; }<\/script>';
 
		}
    }
