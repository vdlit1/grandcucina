 <?php
	class Slink_MageSaasu_Block_Admin_Config_Renderer_Gctaxclassselect extends Mage_Core_Block_Abstract
	{
		protected function _toHtml(){            
            $column = $this->getColumn();
            
            $classes = Mage::getModel('tax/class_source_product')->toOptionArray();
            
            $options = '';
            foreach($classes as $class){
                $options .= '<option value="'.$class['value'].'">'.$class['label'].'</option>';
            }
            
            return '<select id="select_2_#{_id}" class="'.$column['class'].'" style="'.$column['style'].'" name="'.$this->getInputName().'">'.$options.'</select>'.
            '<script type="text\/javascript">var x=document.getElementById("select_2_#{_id}"); var i; for (i=0;i<x.length;i++){ if(x.options[i].value=="#{'.$this->getColumnName().'}") x.selectedIndex=i; }<\/script>';
 
		}
    }
