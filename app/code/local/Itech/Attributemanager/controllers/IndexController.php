<?php
class Itech_Attributemanager_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/attributemanager?id=15 
    	 *  or
    	 * http://site.com/attributemanager/id/15 	
    	 */
    	/* 
		$attributemanager_id = $this->getRequest()->getParam('id');

  		if($attributemanager_id != null && $attributemanager_id != '')	{
			$attributemanager = Mage::getModel('attributemanager/attributemanager')->load($attributemanager_id)->getData();
		} else {
			$attributemanager = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($attributemanager == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$attributemanagerTable = $resource->getTableName('attributemanager');
			
			$select = $read->select()
			   ->from($attributemanagerTable,array('attributemanager_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$attributemanager = $read->fetchRow($select);
		}
		Mage::register('attributemanager', $attributemanager);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}