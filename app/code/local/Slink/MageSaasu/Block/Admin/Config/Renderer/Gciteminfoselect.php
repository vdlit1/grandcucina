<?php
	class Slink_MageSaasu_Block_Admin_Config_Renderer_Gciteminfoselect extends Mage_Core_Block_Abstract
	{
		protected function _toHtml(){            
            $column = $this->getColumn();
            
            $a  = array(    array('label'=>'--None--',
                                  'value'=>''),
                            array('label'=>'SKU',
                                   'value'=>'sku'),
                            array('label'=>'Description',
                                    'value'=>'description'),
                             array('label'=>'Price',
                                   'value'=>'price'),
                            array('label'=>'Category',
                                  'value'=>'category'),
                             array('label'=>'Quanitty',
                                   'value'=>'stock_on_hand'),
							array('label'=>'Buy Unit Quantity',
								  'value'=>'buy_unit_quantity'),
							array('label'=>'Unit Cost Ex',
								  'value'=>'unit_cost_ex'));


            $options='';
            foreach($a as $option){
                $options .= '<option value="'.$option['value'].'">'.$option['label'].'</option>';
            }
            
            return '<select id="select_#{_id}" class="'.$column['class'].'" style="'.$column['style'].'" name="'.$this->getInputName().'">'.$options.'</select>'.
            '<script type="text\/javascript">var x=document.getElementById("select_#{_id}"); var i; for (i=0;i<x.length;i++){ if(x.options[i].value=="#{'.$this->getColumnName().'}") x.selectedIndex=i; }<\/script>';
 
		}
    }
