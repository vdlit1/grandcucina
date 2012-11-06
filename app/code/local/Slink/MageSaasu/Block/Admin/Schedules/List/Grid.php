<?php
	class Slink_MageSaasu_Block_Admin_Schedules_List_Grid extends Mage_Adminhtml_Block_Widget_Grid
	{
		public function __construct(){
			parent::__construct();
			$this->setId('id');
			$this->_controller = 'slink';
		}
		
		protected function _prepareCollection(){
                        
			$model = Mage::getModel('slink/schedules');
			$collection = $model->getCollection();
			$this->setCollection($collection);
			$this->setDefaultSort('sort_order');
			$this->setDefaultDir('ASC');
			return parent::_prepareCollection();
		}

		protected function _prepareMassaction(){
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('schedules');
			$this->getMassactionBlock()->addItem(	'run', array('label'=>Mage::helper('slink')->__('Run'),
																  'url'=>$this->getUrl('*/*/massRun')));
			$this->getMassactionBlock()->addItem(	'delete', array('label'=>Mage::helper('slink')->__('Delete'),
																	'url'=>$this->getUrl('*/*/massDelete')));
			$this->getMassactionBlock()->addItem(	'publish', array('label'=>Mage::helper('slink')->__('Publish'),
																	'url'=>$this->getUrl('*/*/massPublish')));			
			$this->getMassactionBlock()->addItem(	'unpublish', array('label'=>Mage::helper('slink')->__('Unpublish'),
																	'url'=>$this->getUrl('*/*/massUnpublish')));			
		}
		
		protected function _prepareColumns(){
			
			$this->addColumn('sort_order', array(	'header'    	=> Mage::helper('slink')->__('Position'),
												 'align'         => 'left',
												 'style'			=> 'width:10px',
												 'width'			=> '10px',
												 'index'    	 	=> 'sort_order',
												 'type'     	 	=> 'number' ,
												 //													'editable'		=> 'true' // Uncomment when updated to save in Schedules list
												 ));
			
			$this->addColumn('schedule_name', array(
						   'header'        => Mage::helper('slink')->__('Name'),
						   'align'         => 'left',
						   'width'         => '200px',
						   'index'         => 'schedule_name',
						   'type'          => 'text',
						   'truncate'      => 50,
						   'escape'        => true,
						   ));
			$this->addColumn('published', array(	'header'        => Mage::helper('slink')->__('Published'),
												'align'         => 'center',
												'width'			=> '50px',
												'index'         => 'published',
												'type'          => 'text',
												'escape'        => false,
												'renderer'		=> 'slink/admin_slink_list_column_renderer_published'
												));						
			$this->addColumn('schedule_file', array('header'        => Mage::helper('slink')->__('Script'),
													'align'         => 'left',
													'width'			=> '200px',
													'index'         => 'schedule_file',
													'type'          => 'text',
													'escape'        => false,
													));		

			$this->addColumn('mhdmd', array(		'header'        => Mage::helper('slink')->__('MHDMD'),
													'align'         => 'center',
													'index'         => 'mhdmd',
													'type'          => 'text',
													'escape'        => false,
													));
			$this->addColumn('script_limit', array(	'header'        => Mage::helper('slink')->__('Limit'),
												   'align'         => 'center',
												   'index'         => 'script_limit',
												   'type'          => 'text',
												   'escape'        => false,
												   'width'			=> '15px'
												   ));			
			$this->addColumn('log_email', array(	'header'        => Mage::helper('slink')->__('Email'),
												'align'         => 'center',
												'type'          => 'text',
												'width'			=> '20px',												
												'index'			=> 'log_email'
												));			
/*			$this->addColumn('log_file', array(	'header'        => Mage::helper('slink')->__('Log'),
												'align'         => 'center',
												'width'			=> '20px',
												'type'          => 'text',
												'index'			=> 'log_file',											   
												'renderer'		=> 'slink/admin_slink_list_column_renderer_published'
												));						*/
			$this->addColumn('last_run', array(	'header'    	=> Mage::helper('slink')->__('Last run at'),
												'align'         => 'left',
												'index'    	 	=> 'last_run',
												'type'     	 	=> 'date',
												'format'		=> 'Y-M-d h:m:s',											   
												'escape'		=> true,
												'renderer'	=> 'slink/admin_slink_list_column_renderer_date'
												));

			
			$this->addColumn('actions', array(
								   'header'    => Mage::helper('slink')->__('Action'),
								   'width'     => '150px',
								   'type'      => 'action',
								   'getter'	=> 'getId',
											  'actions'   => array(array('caption' => Mage::helper('slink')->__('Run'),
																		  'url'     => array('base'=>'*/*/run'),
																		  'field'   => 'id' ),																									   
																	array('caption' => Mage::helper('slink')->__('Edit'),
																		  'url'     => array('base'=>'*/*/edit'),
																		  'field'   => 'id' ),								
																	array('caption' => Mage::helper('slink')->__('Delete'),
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
