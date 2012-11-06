<?php
	class Slink_MageSaasu_Block_Admin_Sales_List_Grid extends Mage_Adminhtml_Block_Widget_Grid
	{
		public function __construct(){
			parent::__construct();
			$this->setId('id');
			$this->_controller = 'slink';
		}
		
		protected function _prepareCollection(){
            
            $collection = Mage::getModel('slink/sales')->getCollection();
            
            $entityType = Mage::getModel('eav/entity_type')->loadByCode('order');
            $attributes = $entityType->getAttributeCollection();
            $entityTable = $collection->getTable($entityType->getEntityTable());
            
            $collection->getSelect()->joinLeft($entityTable, 'main_table.'.'vid = '.$entityTable.'.entity_id');

            $collection->getSelect()->joinLeft(array('billing'=>Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                                               'sales_flat_order.entity_id = billing.parent_id AND billing.address_type="billing"',
                                               array('CONCAT(billing.lastname, ", ", billing.firstname) as billing_name')) ;
            $collection->getSelect()->joinLeft(array('shipping'=>Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                                               'sales_flat_order.entity_id = shipping.parent_id AND shipping.address_type="shipping"',
                                               array('CONCAT(shipping.lastname, ", ", shipping.firstname) as shipping_name')) ;
            $collection->addFilterToMap('billing_name', 'billing.lastname');
            $collection->addFilterToMap('shipping_name', 'shipping.lastname');
            $collection->getSelect()->group('id');

//			echo $collection->printLogQuery(true);
            
			$this->setCollection($collection);
			$this->setDefaultSort('vid');
			$this->setDefaultDir('DESC');
			
			return parent::_prepareCollection();
		}
		protected function _prepareMassaction(){
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('sales');
			$this->getMassactionBlock()->addItem(	'link', array('label'=>Mage::helper('slink')->__('Link'),
																  'url'=>$this->getUrl('*/*/massLink')));
			$this->getMassactionBlock()->addItem(	'unlink', array('label'=>Mage::helper('slink')->__('Unlink'),
																	'url'=>$this->getUrl('*/*/massUnlink')));
			
		}
		
		protected function _prepareColumns(){															

			$this->addColumn('increment_id', array(
						   'header'        => Mage::helper('slink')->__('Order No'),
						   'align'         => 'left',
						   'width'         => '100px',
						   'index'         => 'increment_id',
						   'type'          => 'text',
						   ));
			$this->addColumn('invoice_number', array(
											  'header'        => Mage::helper('slink')->__('Linked Invoice No'),
											  'align'         => 'left',
											  'width'         => '100px',
											  'index'         => 'invoice_number',
											  'type'          => 'text',
											  'escape'        => true,
											  ));
			$this->addColumn('billing_name', array(	'header'        => Mage::helper('slink')->__('Billing'),
												'align'         => 'left',
												'width'         => '200px',
												'type'          => 'text',
												'escape'        => true,
                                                'index'           => 'billing_name'
												 ));			
			$this->addColumn('shipping', array(	'header'        => Mage::helper('slink')->__('Shipping'),
												'align'         => 'left',
												'width'         => '200px',
												'type'          => 'text',
                                                'index'         => 'shipping_name',
												'escape'        => true
												));						
			$this->addColumn(	'grand_total', array(	'header'        => Mage::helper('slink')->__('Total $'),
														'align'         => 'right',
														'width'         => '70px',
														'index'         => 'grand_total',
														'type'          => 'number',
														'renderer'		=> 'slink/admin_sales_list_column_renderer_price'
														
											  ));		
			$this->addColumn(	'order_currency_code', array(	'header'        => Mage::helper('slink')->__('Curr\'y'),
																'width'         => '20px',
																'type'          => 'text',
                                                                'index'         => 'order_currency_code',
																'escape'        => true
													 ));					
			
			$this->addColumn(	'status', array(		'header'		=> Mage::helper('slink')->__('Status'),
														'align'			=> 'center',
														'width'			=> '50px',
														'index'			=> 'transferred',							
														'filter'		=> false,
														'renderer'		=>	'slink/admin_sales_list_column_renderer_status'
											 ));			
			$this->addColumn(	'transferred', array(	'header'    => Mage::helper('slink')->__('Linked at (yyyy-mm-dd)'),
													 'align'        => 'left',
													 'index'        => 'transferred',
													 'width'		=> '150px',													 
													 'type'			=> 'text',
													 'renderer'     => 'slink/admin_slink_list_column_renderer_date'
													 ));
			

			$this->addColumn(	'actions', array(		'header'    => Mage::helper('slink')->__('Action'),
														'width'     => '150px',
														'type'      => 'action',
														'getter'	=> 'getId',
														'actions'   =>	array(array('caption' => Mage::helper('slink')->__('View'),
																				  'url'     => array('base'=>'*/*/view'),
																				  'field'   => 'id' ),
																		array('caption' => Mage::helper('slink')->__('Link'),
																			  'url'		=> array('base'=>'*/*/link'),
																			  'field'	=> 'id'),
																		array('caption' => Mage::helper('slink')->__('Unlink'),
																			  'url'		=> array('base'=>'*/*/unlink'),
																			  'field'	=> 'id'),														
														'filter'    => false,
														'sortable'  => false)
														));
			return parent::_prepareColumns();
		}
		
		public function getRowUrl($row)
		{
			return $this->getUrl('*/*/view', array('id' => $row->getId()));		
		}		
	}
