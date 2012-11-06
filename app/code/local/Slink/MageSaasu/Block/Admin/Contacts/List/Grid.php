<?php
	class Slink_MageSaasu_Block_Admin_Contacts_List_Grid extends Mage_Adminhtml_Block_Widget_Grid
	{
		public function __construct(){
			parent::__construct();
			$this->setId('slink_magesaasu_block_admin_contacts_list_grid');
			$this->_controller = 'slink';
		}

		protected function _prepareCollection(){
            
            $collection = Mage::getModel('slink/contacts')->getCollection();
            $entityType = Mage::getModel('eav/entity_type')->loadByCode('customer_address');
            $attributes = $entityType->getAttributeCollection();
            $entityTable = $collection->getTable($entityType->getEntityTable());
            
            $index = 1;
            foreach($attributes->getItems() as $attribute){
                
                switch($attribute->getAttributeCode()){
                    case 'firstname':
                    case 'lastname':
                    case 'middlename':
                    default:
                        $alias = 'table'.$index;
                        if($attribute->getBackendType() != 'static'){
                            $table = $entityTable.'_'.$attribute->getBackendType();
                            $field = $alias.'.value';
                            $collection->getSelect()
                            ->joinLeft(array($alias=>$table),
                                       'main_table.'.'vid = '.$alias.'.entity_id and '.$alias.'.attribute_id = '.$attribute->getAttributeId(),
                                       array($attribute->getAttributeCode() => $field)
                                       );
                        }
                        break;
                }
                $index++;
            }
            $collection->getSelect()->joinLeft($entityTable, 'main_table.'.'vid = '.$entityTable.'.entity_id');
            

            
            $collection->getSelect()->columns(array("name"=>"CONCAT(table6.value, ', ', table5.value)"));
			$this->setCollection($collection);
            
//            echo $collection->printLogQuery(true);
            
			$this->setDefaultSort('vid');
			$this->setDefaultDir('DESC');
			return parent::_prepareCollection();
		}
		protected function _prepareMassaction(){
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('contacts');
			$this->getMassactionBlock()->addItem(	'link', array('label'=>Mage::helper('slink')->__('Link'),
																  'url'=>$this->getUrl('*/*/massLink')));
			$this->getMassactionBlock()->addItem(	'unlink', array('label'=>Mage::helper('slink')->__('Unlink'),
																  'url'=>$this->getUrl('*/*/massUnlink')));
			
		}
		
		protected function _prepareColumns(){
			$this->addColumn(	'vid', array(
											 'header'        => Mage::helper('slink')->__('Address ID'),
											 'width'         => '30px',
											 'index'         => 'vid',
											 'type'          => 'text',
											 'escape'        => true
											 ));
			$this->addColumn(	'parent_id', array(
											 'header'        => Mage::helper('slink')->__('Customer ID'),
											 'width'         => '30px',
											 'index'         => 'parent_id',
											 'type'          => 'text',
											 'escape'        => true
											 ));

			$this->addColumn('address_type', array(
												'header'        => Mage::helper('slink')->__('Address Type'),
												'align'         => 'left',
												'width'         => '150px',
												'index'         => 'address_type',
												'type'          => 'text',
												'truncate'      => 50,
												'escape'        => true,
                                                'filter'        => false,
                                                'sortable'      => false,
												'renderer'	  => 'slink/admin_contacts_list_column_renderer_addresstype'
											  ));
			
			$this->addColumn(	'contact', array(		'header'        => Mage::helper('slink')->__('Name'),
												 'align'         => 'left',
												 'width'         => '500px',
												 'index'         => 'name',
												 'type'          => 'text',
												 'truncate'      => 50,
												 'escape'        => true,

												 ));	
			

			$this->addColumn(	'status', array(	'header'		=> Mage::helper('slink')->__('Status'),
													'align'		=> 'center',
													'width'		=> '50px',
													'index'		=> 'transferred',
													'filter'	=> false,
													'renderer'=>	'slink/admin_contacts_list_column_renderer_status'
											 ));			
			$this->addColumn(	'transferred', array(	'header'        => Mage::helper('slink')->__('Linked at (yyyy-mm-dd)'),
														'align'         => 'left',
														'width'			=> '150px',
														'index'         => 'transferred',
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
															  'url'     => array('base'=>'*/*/link'),
															  'field'   => 'id' ),
														array('caption' => Mage::helper('slink')->__('Unlink'),
															  'url'     => array('base'=>'*/*/unlink'),
															  'field'   => 'id' ),														
														'filter'    => false,
														'sortable'  => false)));
			return parent::_prepareColumns();
		}
		
		public function getRowUrl($row)
		{
			return $this->getUrl('*/*/view', array('id' => $row->getId()));		
		}		
	}
