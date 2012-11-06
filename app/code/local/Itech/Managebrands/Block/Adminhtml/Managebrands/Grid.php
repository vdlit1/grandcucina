<?php

class Itech_Managebrands_Block_Adminhtml_Managebrands_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('managebrandsGrid');
      $this->setDefaultSort('managebrands_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('managebrands/managebrands')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('managebrands_id', array(
          'header'    => Mage::helper('managebrands')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'managebrands_id',
      ));

      $this->addColumn('title', array(
          'header'    => Mage::helper('managebrands')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));
	  
	  $this->addColumn('filename', array(
          'header'    => Mage::helper('managebrands')->__('Logo'),
          'align'     =>'left',
          'index'     => 'filename',
       	  'filter'    => false,
      	  'renderer'	=> new Itech_Managebrands_Block_Adminhtml_Renderer_Logo(),
      ));
	  
	  $this->addColumn('link', array(
          'header'    => Mage::helper('managebrands')->__('Link'),
          'align'     =>'left',
       	  'filter'    => false,
      	  'renderer'	=> new Itech_Managebrands_Block_Adminhtml_Renderer_Link(),
      ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('managebrands')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('managebrands')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('managebrands_id');
        $this->getMassactionBlock()->setFormFieldName('managebrands');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('managebrands')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('managebrands')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('managebrands/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}