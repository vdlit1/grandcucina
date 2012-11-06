<?php

class Turnto_Admin_AdminControllers_TurntoController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		$this->loadLayout();

		$this->_addLeft($this->getLayout()->createBlock('Turnto_Admin_Block_ShowTabsAdminBlock'));
		
		$this->renderLayout();
	}
	
	public function redirectAction()
	{
		$this->_redirectUrl('http://www.turnto.com');
	}
	
	public function postAction()
    {
        $post = $this->getRequest()->getPost();
        $catalogFeed = true;
        
        try {
            if (empty($post)) {
                Mage::throwException($this->__('Invalid form data.'));
            }
            
            $path = Mage::getBaseDir('media') . DS . 'turnto/';
            mkdir($path, 0755);
            
            if($post['feed_type'] == 'historical'){            
				/* form processing */        
				$startDate = $post['start_date'];
				
				if($startDate == null || $startDate == ""){
					Mage::getSingleton('adminhtml/session')->addError("Start Date is required");
					$this->_redirect('*/*/', array('active_tab' => 'turnto_hist_feed_tab'));
					return;
				}
				
				$resource = Mage::getSingleton('core/resource');     
				$readConnection = $resource->getConnection('core_read');
				/*$result = $readConnection->fetchAll(
					 'select '
					.'  sales_flat_order.entity_id as orderid, '
					.'  sales_flat_order_item.created_at as orderdate, '
					.'  customer_email as email, '
					.'  sales_flat_order_item.name as itemtitle, '
					.'  concat(\'http://magento.turntodev.com/index.php/\', url_path) as itemurl, '
					.'  item_id as itemlineid, '
					.'  postcode as zip, '
					.'  customer_firstname as firstname, '
					.'  customer_lastname as lastname, '
					.'  ifnull(parent.sku, sales_flat_order_item.sku) as sku, '
					.'  grand_total as price, '
					.'  concat((select concat(value,\'/media/catalog/product/\') from core_config_data where path = \'web/unsecure/base_url\'), small_image) as itemimageurl '
					.'from sales_flat_order, '
					.'  sales_flat_order_item, '
					.'  sales_flat_order_address, '
					.'  catalog_product_flat_1 '
					.'where sales_flat_order.entity_id = sales_flat_order_item.order_id '
					.'  and sales_flat_order.entity_id = sales_flat_order_address.entity_id '
					.'  and sales_flat_order.entity_id = catalog_product_flat_1.entity_id'
					.'  and sales_flat_order_item.created_at > str_to_date(\''.$startDate.'\', \'%m/%d/%Y\')');
		 		*/
				
				$result = $readConnection->fetchAll(
					'select sales_flat_order.entity_id as orderid, '
       						.'sales_flat_order_item.created_at as orderdate, '
       						.'customer_email as email, '
       						.'ifnull(parent.name, sales_flat_order_item.name) as itemtitle, ' 
       						.'concat(\'http://magento.turntodev.com/index.php/\', ifnull(parent.url_path, catalog_product_flat_1.url_path)) as itemurl, '
       						.'item_id as itemlineid, '
       						.'postcode as zip, '
     						.'customer_firstname as firstname, '
       						.'customer_lastname as lastname, '
       						.'ifnull(parent.sku, sales_flat_order_item.sku) as sku, '
       						.'grand_total as price, '
       						.'concat((select concat(value,\'/media/catalog/product/\') from core_config_data where path = \'web/unsecure/base_url\'), ifnull(parent.small_image, catalog_product_flat_1.small_image)) as itemimageurl '
     					.'from sales_flat_order,'
       						.'sales_flat_order_item, '
       						.'sales_flat_order_address, '       
       						.'catalog_product_flat_1 '
       						.'left join catalog_product_super_link on catalog_product_flat_1.entity_id = catalog_product_super_link.product_id '
      						.'left join catalog_product_flat_1 as parent on catalog_product_super_link.parent_id = parent.entity_id '
     					.'where sales_flat_order.entity_id = sales_flat_order_item.order_id '
       						.'and sales_flat_order.entity_id = sales_flat_order_address.entity_id '
       						.'and sales_flat_order_item.product_id = catalog_product_flat_1.entity_id '
       						.'and sales_flat_order_item.created_at > str_to_date(\''.$startDate.'\', \'%m/%d/%Y\')');
				
				$handle = fopen($path . 'histfeed.csv', 'w');
				
				fwrite($handle, "ORDERID\tORDERDATE\tEMAIL\tITEMTITLE\tITEMURL\tITEMLINEID\tZIP\tFIRSTNAME\tLASTNAME\tSKU\tPRICE\tITEMIMAGEURL");
				fwrite($handle, "\n");
				
				foreach($result as $row){
					foreach($row as $column){
						fwrite($handle, $column);
						fwrite($handle, "\t");
					}
					
					fwrite($handle, "\n");
				}
				
				fclose($handle); 
							
				$message = $this->__('The historical feed was successfully generated. Click the &quot;Download historical feed&quot; link to download.');
				$catalogFeed = false;
            }
            else{
            	/* form processing */        
				
				$resource = Mage::getSingleton('core/resource');     
				$readConnection = $resource->getConnection('core_read');
/*
				$result = $readConnection->fetchAll(
                                        '(select  sku as SKU, '
                                        .' concat((select concat(value, \'media/catalog/product\') from core_config_data where path = \'web/unsecure/base_url\'), small_image) as IMAGEURL, '
                                        .' name as TITLE, '
                                        .' price as PRICE, '
                                        .' \'\' as CURRENCY, '
                                        .' \'Y\' as ACTIVE, '
                                        .' concat((select concat(value, \'index.php/\') from core_config_data where path = \'web/unsecure/base_url\'), url_path) as ITEMURL, '
                                        .' (select ifnull(group_concat(category_id), \'\') from catalog_category_product where product_id = entity_id) as CATEGORY, '
                                        .' \'\' as KEYWORDS, '
                                        .' \'\' as REPLACEMENTSKU, '
                                        .' \'Y\' as INSTOCK, '
                                        .' \'\' as VIRTUALPARENTCODE, '
                                        .' \'\' as CATEGORYPATHJSON, '
                                        .' \'N\' as ISCATEGORY from catalog_product_flat_1) '
                                        .'UNION ALL '
                                        .'(select  entity_id as SKU, '
                                        .' \'\' as IMAGEURL, '
                                        .' name as TITLE, '
                                        .' \'\' as PRICE, '
                                        .' \'\' as CURRENCY, '
                                        .' \'Y\' as ACTIVE, '
                                        .' concat((select concat(value, \'index.php/\') from core_config_data where path = \'web/unsecure/base_url\'), url_path) as ITEMURL, '
                                        .' (select ifnull(parent_id, \'\') from catalog_category_entity where entity_id = ccfs.entity_id) as CATEGORY, '
                                        .' \'\' as KEYWORDS, '
                                        .' \'\' as REPLACEMENTSKU, '.'\'Y\' as INSTOCK, '
                                        .' \'\' as VIRTUALPARENTCODE, '
                                        .' \'\' as CATEGORYPATHJSON, '
                                        .' \'Y\' as ISCATEGORY '
                                        .'from catalog_category_flat_store_1 ccfs)');
*/

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
                                        .' \'n\' as ISCATEGORY from catalog_product_flat_1 where not exists (select 1 from catalog_product_super_link where product_id = catalog_product_flat_1.entity_id)) '
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

				$handle = fopen($path . 'catfeed.csv', 'w');
				
				fwrite($handle, "SKU\tIMAGEURL\tTITLE\tPRICE\tCURRENCY\tACTIVE\tITEMURL\tCATEGORY\tKEYWORDS\tREPLACEMENTSKU\tINSTOCK\tVIRTUALPARENTCODE\tCATEGORYPATHJSON\tISCATEGORY");
				fwrite($handle, "\n");
				
				foreach($result as $row){
					foreach($row as $column){
						fwrite($handle, $column);
						fwrite($handle, "\t");
					}
					
					fwrite($handle, "\n");
				}
				
				fclose($handle); 
							
				$message = $this->__('The catalog feed was successfully generated. Click the &quot;Download catalog feed&quot; link to download.');
            }
            Mage::getSingleton('adminhtml/session')->addSuccess($message);
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        
        if($catalogFeed){
			$this->_redirect('*/*/', array('active_tab' => 'turnto_catalog_feed_tab'));
		}else{
			$this->_redirectUrl(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'media/turnto/histfeed.csv');
		}
    }
}
