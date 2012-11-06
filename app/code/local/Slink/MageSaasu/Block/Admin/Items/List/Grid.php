<?php
	class Slink_MageSaasu_Block_Admin_Items_List_Grid extends Mage_Adminhtml_Block_Widget_Grid
	{
		public function __construct(){
			parent::__construct();
			$this->setId('id');
			$this->_controller = 'slink';
		}
		
		protected function _prepareCollection(){
			
            $collection = Mage::getModel('slink/items')->getCollection();

            $entityType = Mage::getModel('eav/entity_type')->loadByCode('catalog_product');
            $attributes = $entityType->getAttributeCollection();
            $entityTable = $collection->getTable($entityType->getEntityTable());
            
            $index = 1;
            foreach($attributes->getItems() as $attribute){                
                switch($attribute->getAttributeCode()){
                    case 'sku':
                    case 'name':
                    case 'price':
                        $alias = 'table'.$index;
                        if($attribute->getBackendType() != 'static'){
                            
                            $collection->addFilterToMap($attribute->getAttributeCode(), 'table'.$index.'.value');
                            
                            $table = $entityTable.'_'.$attribute->getBackendType();
                            $field = $alias.'.value';
                            $collection->getSelect()
                            ->joinLeft(array($alias=>$table),
                                       'main_table.'.'vid = '.$alias.'.entity_id and '.$alias.'.attribute_id = '.$attribute->getAttributeId(),
                                       array($attribute->getAttributeCode() => $field)
                                       );
                        }
                        break;
                    default:break;
                }
                $index++;
            }
            $collection->getSelect()->joinLeft($entityTable, 'main_table.'.'vid = '.$entityTable.'.entity_id');
            
            $collection->getSelect()->where('type_id="simple"');
            $collection->getSelect()->group('main_table.id');
			$this->setCollection($collection);
//            echo $collection->printLogQuery(true);
            
            $this->setDefaultSort('e.name');                        
            $this->setDefaultDir('DESC');                                    
			return parent::_prepareCollection();
		}
		
		protected function _prepareMassaction(){
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('items');
			$this->getMassactionBlock()->addItem(	'link', array('label'=>Mage::helper('slink')->__('Link'),
																  'url'=>$this->getUrl('*/*/massLink')));
			$this->getMassactionBlock()->addItem(	'unlink', array('label'=>Mage::helper('slink')->__('Unlink'),
																	'url'=>$this->getUrl('*/*/massUnlink')));
			
		}
		
		protected function _prepareColumns(){
			$this->addColumn('entity_id', array(
						   'header'        => Mage::helper('slink')->__('Product ID'),
						   'width'         => '50px',
						   'index'         => 'entity_id',
						   'type'          => 'number',
						   'truncate'      => 50,
						   'escape'        => true,
						   ));

			$this->addColumn('name', array(
											  'header'        => Mage::helper('slink')->__('Name'),
											  'align'         => 'left',
											  'width'         => '500px',
											  'index'         => 'name',
											  'type'          => 'text',
											  'escape'        => true
											  ));
			$this->addColumn('sku', array('header'        => Mage::helper('slink')->__('SKU'),
										  'align'         => 'left',
										  'width'         => '200px',
										  'index'         => 'sku',
										  'type'          => 'text',
										  'truncate'      => 50,
										  'escape'        => true,
										  ));
			$this->addColumn('price', array(
											  'header'        => Mage::helper('slink')->__('Price $'),
											  'align'         => 'center',
											  'width'         => '50px',
											  'index'         => 'price',
											  'type'          => 'currency',
											  'escape'        => true
											  ));			
			
			$this->addColumn('status', array('header'		=> Mage::helper('slink')->__('Status'),
											 'align'		=> 'center',
											 'width'		=> '50px',
											 'index'        => 'transferred',											 
											 'filter'		=> false,
											 'renderer'=>	'slink/admin_items_list_column_renderer_status'
							 ));
			$this->addColumn('transferred', array(	'header'        => Mage::helper('slink')->__('Linked at (yyyy-mm-dd)'),
													'align'         => 'left',
													'index'         => 'transferred',
													'width'		  => '200px',
													'type'          => 'text',
													'escape'        => false,
													'renderer'		=> 'slink/admin_slink_list_column_renderer_date'
											  ));
					
			$this->addColumn('actions', array(
								   'header'    => Mage::helper('slink')->__('Action'),
								   'width'     => '150px',
								   'type'      => 'action',
								   'getter'	=> 'getId',
								   'actions'   => array(array('caption' => Mage::helper('slink')->__('View'),
															  'url'     => array('base'=>'*/*/edit'),
															  'field'   => 'id' ),
														array('caption' => Mage::helper('slink')->__('Link'),
															  'url'		=> array('base'=>'*/*/link'),
															  'field'	=> 'id' ),
														array('caption' => Mage::helper('slink')->__('Unlink'),
															  'url'		=> array('base'=>'*/*/unlink'),
															  'field'	=> 'id' ),
														'filter'    => false,
														'sortable'  => false)));
			return parent::_prepareColumns();
		}
		
		public function getRowUrl($row)
		{
			return $this->getUrl('*/*/view', array('id' => $row->getId()));		
		}		
	}
