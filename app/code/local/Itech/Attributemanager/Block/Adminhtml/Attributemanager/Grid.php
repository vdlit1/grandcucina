<?php

class Itech_Attributemanager_Block_Adminhtml_Attributemanager_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('attributemanagerGrid');
      $this->setDefaultSort('attributemanager_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('attributemanager/attributemanager')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
  	  $resource = Mage::getSingleton('core/resource'); 
	  $read = $resource->getConnection('catalog_read');
	  $att = $resource->getTableName('attributemanager');
	  $ev  = $resource->getTableName('eav_attribute_option_value');
	  
	  $arr_mn = array();
	  
	  $option = $read->select() 
					 ->from(array('at'=>$att), '*');
	  $op = $read	 ->fetchAll($option);
	  
	  foreach ($op as $item) {
			$id   = $item['option_id'];
			
			$sql = $read->select()->distinct(true)->from(array('ev'=>$ev), 'value')->where('option_id = '.$id);
			
			$mn = $read->fetchAll($sql);
			
			foreach($mn as $_item)
				{
					$manufacturer = $_item['value'];
				}
			$arr_mn[$id] = $manufacturer;
	  } 
	  
      $this->addColumn('attributemanager_id', array(
          'header'    => Mage::helper('attributemanager')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'attributemanager_id',
      ));
	  

      $this->addColumn('option_id', array(
          'header'    => Mage::helper('attributemanager')->__('Used by'),
          'index'     => 'option_id',
		  'type'      => 'options',
          'options'   => $arr_mn
      ));
	  
	  $this->addColumn('filename', array(
          'header'    => Mage::helper('attributemanager')->__('Image'),
          'align'     =>'left',
          'index'     => 'filename',
       	  'filter'    => false,
      	  'renderer'	=> new Itech_Attributemanager_Block_Adminhtml_Renderer_Logo(),
      ));
  
	  $this->addColumn('url', array(
          'header'    => Mage::helper('attributemanager')->__('Url'),
          'align'     =>'left',
          'index'     => 'url',
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('attributemanager')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('attributemanager')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('attributemanager')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('attributemanager')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('attributemanager_id');
        $this->getMassactionBlock()->setFormFieldName('attributemanager');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('attributemanager')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('attributemanager')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('attributemanager/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('attributemanager')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('attributemanager')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}