<?php

	class Slink_MageSaasu_Block_Admin_Items_View extends Mage_Adminhtml_Block_Widget_Form_Container
	{
		public function __construct()
		{	
			parent::__construct();
			
			$this->_blockGroup = 'slink';
			$this->_mode = 'view';
			$this->_controller = 'admin_items';
						
			if( $this->getRequest()->getParam($this->_objectId) ) {
				$item = Mage::getModel('slink/items')->load($this->getRequest()->getParam($this->_objectId));
				$info = Mage::getModel('catalog/product')->load($item->getData('vid'));
				
				if($item->getData('id') > 0 && $info->getData('entity_id') > 0){
					$iteminfo = $item->getData();
					$itemdetail = $info->getData();
					$inventoryinfo = $itemdetail['stock_item']->getData();
					
					Mage::register('current_item', array('id'			=>$iteminfo['id'],
														 'vid'			=>$iteminfo['vid'],
														 'entity_uid'	=>$iteminfo['entity_uid'],
														 'updated_uid'	=>$iteminfo['updated_uid'],
														 'transferred'	=>Mage::getModel('core/date')->date('Y-m-d H:i:s', $iteminfo['transferred']),
														 'entity_id'	=>$itemdetail['entity_id'],
														 'type_id'		=>$itemdetail['type_id'],
														 'sku'			=>$itemdetail['sku'],
														 'price'		=>$itemdetail['price'],
														 'created_at'	=>Mage::getModel('core/date')->date('Y-m-d H:i:s', $itemdetail['created_at']),
														 'updated_at'	=>Mage::getModel('core/date')->date('Y-m-d H:i:s', $itemdetail['updated_at']),
														 'name'			=>$itemdetail['name'],
														 'description'	=>$itemdetail['description'],
														 'short_description'=>$itemdetail['short_description'],
														 'qty'			=>$inventoryinfo['qty'],
														 'min_qty'		=>$inventoryinfo['min_qty'],
														 'backorders'	=>$inventoryinfo['backorders'],
														 'is_in_stock'	=>$inventoryinfo['is_in_stock'],
														 'notify_stock_qty'=>$inventoryinfo['notify_stock_qty']
														 ));	
				}
				
			}
			
			$this->_removeButton('save');
			$this->_removeButton('delete');			
			$this->_removeButton('reset');						
		}			
			
		
		public function getHeaderText()
		{
			return Mage::helper('slink')->__("View Item");			
		}
	}
