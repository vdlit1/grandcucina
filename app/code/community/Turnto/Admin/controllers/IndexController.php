<?php

class Turnto_Admin_IndexController extends Mage_Core_Controller_Front_Action{
    public function indexAction(){
        $resource = Mage::getSingleton('core/resource');     
		$readConnection = $resource->getConnection('core_read');

		 $result = $readConnection->fetchAll(
                                        '(select  sku as SKU, '
                                        .' concat((select concat(value, \'media/catalog/product\') from core_config_data where path = \'web/unsecure/base_url\'), small_image) as IMAGEURL, '
                                        .' name as TITLE, '
                                        .' price as PRICE, '
                                        .' \'\' as CURRENCY, '
                                        .' \'Y\' as ACTIVE, '
                                        .' concat((select concat(value, \'index.php/\') from core_config_data where path = \'web/unsecure/base_url\'), url_path) as ITEMURL, '
                                        .' (select ifnull(group_concat(category_id), \'\') from catalog_category_product where product_id = entity_id and category_id <> \'1\') as CATEGORY, '
                                        .' \'\' as KEYWORDS, '
                                        .' \'\' as REPLACEMENTSKU, '
                                        .' \'Y\' as INSTOCK, '
                                        .' \'\' as VIRTUALPARENTCODE, '
                                        .' \'\' as CATEGORYPATHJSON, '
					.' \'n\' as ISCATEGORY from catalog_product_flat_1) '
					.'UNION ALL '
                                        .'(select  entity_id as SKU, '
                                        .' \'\' as IMAGEURL, '
                                        .' name as TITLE, '
                                        .' \'\' as PRICE, '
                                        .' \'\' as CURRENCY, '
                                        .' \'Y\' as ACTIVE, '
                                        .' concat((select concat(value, \'index.php/\') from core_config_data where path = \'web/unsecure/base_url\'), url_path) as ITEMURL, '
                                        .' (select ifnull(parent_id, \'\') from catalog_category_entity where entity_id = ccfs.entity_id and parent_id <> \'1\') as CATEGORY, '
                                        .' \'\' as KEYWORDS, '
                                        .' \'\' as REPLACEMENTSKU, '.'\'Y\' as INSTOCK, '
                                        .' \'\' as VIRTUALPARENTCODE, '
					.' \'\' as CATEGORYPATHJSON, '
					.' \'y\' as ISCATEGORY '
                                        .'from catalog_category_flat_store_1 ccfs where entity_id <> \'1\')');
	
		echo "SKU\tIMAGEURL\tTITLE\tPRICE\tCURRENCY\tACTIVE\tITEMURL\tCATEGORY\tKEYWORDS\tREPLACEMENTSKU\tINSTOCK\tVIRTUALPARENTCODE\tCATEGORYPATHJSON\tISCATEGORY";
		echo "\n";
		
		foreach($result as $row){
			foreach($row as $column){
				echo $column;
				echo "\t";
			}
			
			echo "\n";
		}
  }
}


