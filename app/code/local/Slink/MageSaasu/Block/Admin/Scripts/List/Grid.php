<?php
	class Slink_MageSaasu_Block_Admin_Scripts_List_Grid extends Mage_Adminhtml_Block_Widget_Grid
	{
		public function __construct(){
			parent::__construct();
			$this->setId('id');
			$this->_controller = 'slink';
		}
		
		protected function _prepareCollection(){
			$collection = Mage::getModel('slink/scripts')->getCollection();

			$this->setCollection($collection);
			$this->setDefaultSort('title');
			$this->setDefaultDir('ASC');
			return parent::_prepareCollection();
		}
		
		protected function _prepareMassaction(){
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('scripts');
			$this->getMassactionBlock()->addItem(	'delete', array('label'=>Mage::helper('slink')->__('Delete'),
																  'url'=>$this->getUrl('*/*/massDelete')));
			$this->getMassactionBlock()->addItem(	'publish', array('label'=>Mage::helper('slink')->__('Publish'),
																	   'url'=>$this->getUrl('*/*/massPublish')));	
			$this->getMassactionBlock()->addItem(	'unpublish', array('label'=>Mage::helper('slink')->__('Unpublish'),
																	'url'=>$this->getUrl('*/*/massUnpublish')));
			
		}
		
		protected function _prepareColumns(){
			
			$this->addColumn('title', array(
						   'header'        => Mage::helper('slink')->__('Name'),
						   'align'         => 'left',
						   'width'         => '150px',
						   'index'         => 'title',
						   'type'          => 'text',
						   'truncate'      => '255',
						   'escape'        => true,
						   ));
			$this->addColumn('published', array(
												'header'        => Mage::helper('slink')->__('Published'),
												'align'         => 'center',
												'width'         => '30px',
												'index'         => 'published',
												'type'          => 'text',
												'truncate'      => '255',
												'escape'        => true,
												'renderer'		=> 'slink/admin_slink_list_column_renderer_published'												
												));						
			$this->addColumn('description', array(	'header'        => Mage::helper('slink')->__('Description'),
													'width'			=> '300px',
													'align'         => 'left',
													'index'         => 'description',
													'type'          => 'text',
													'escape'        => false,
													));
				
			$this->addColumn('file', array(
										   'header'        => Mage::helper('slink')->__('File'),
											'align'         => 'left',
											'width'         => '200px',
											'index'         => 'file',
											'type'          => 'text',
											'truncate'      => '255',
											'escape'        => true,
											));
			
			$this->addColumn('version', array(
										   'header'        => Mage::helper('slink')->__('Version'),
										   'align'         => 'left',
										   'width'         => '50px',
										   'index'         => 'version',
										   'type'          => 'text',
										   'truncate'      => '10',
										   'escape'        => true,
										   ));

			$this->addColumn('created', array(	'header'    	=> Mage::helper('slink')->__('Created'),
												'align'         => 'left',
												'index'    	 	=> 'created',
												'type'     	 	=> 'date',
												'format'		=> 'Y-M-d h:m:s',
												'escape'		=> true,
												'renderer'		=> 'slink/admin_slink_list_column_renderer_date'											  
											   ));
			
			$this->addColumn('actions', array(
								   'header'    => Mage::helper('slink')->__('Action'),
								   'width'     => '150px',
								   'type'      => 'action',
								   'getter'	=> 'getId',
								   'actions'   => array(array('caption' => Mage::helper('slink')->__('Delete'),
															  'url'     => array('base'=>'*/*/delete'),
															  'field'   => 'id'),
														array('caption' => Mage::helper('slink')->__('Publish'),
															  'url'     => array('base'=>'*/*/publish'),
															  'field'   => 'id'),
														array('caption' => Mage::helper('slink')->__('Unpublish'),
															  'url'     => array('base'=>'*/*/unpublish'),
															  'field'   => 'id')),
								   'filter'    => false,
								   'sortable'  => false));
			
			return parent::_prepareColumns();
		}
		
		public function getRowUrl($row)
		{
			return $this->getUrl('*/*/edit', array('id' => $row->getId()));		
		}		
	}
