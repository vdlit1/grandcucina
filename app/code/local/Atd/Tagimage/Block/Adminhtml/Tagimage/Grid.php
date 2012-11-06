<?php

class Atd_Tagimage_Block_Adminhtml_Tagimage_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('tagimageGrid');
      $this->setDefaultSort('tagimage_id');
      $this->setDefaultDir('ASC');
      $this->setUseAjax(true);
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('tagimage/tagimage')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('tagimage_id', array(
          'header'    => Mage::helper('tagimage')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'tagimage_id',
      ));


            $this->addColumn('file',
                array(
                    'header'=> Mage::helper('catalog')->__('Image'),
                    'type'  => 'image',
                    'index' => 'file',
                    'width' => '60px',
                    'renderer' => 'tagimage/adminhtml_tagimage_grid_renderer_image'
            ));
    


      $this->addColumn('label', array(
          'header'    => Mage::helper('tagimage')->__('Label'),
          'align'     =>'left',
          'index'     => 'label',
      ));

       $this->addColumn('position', array(
          'header'    => Mage::helper('tagimage')->__('position'),
          'align'     =>'left',
          'width' => '50px',
          'index'     => 'position',
      ));

        $this->addColumn('disabled', array(
          'header'    => Mage::helper('tagimage')->__('Disabled'),
          'align'     =>'left',
          'width' => '50px',
          'index'     => 'disabled',
          'type'      => 'options',
          'options'   => array(
              0 => 'Enabled',
              1 => 'Disabled',
          ),
      ));




	$this->addColumn('action',
            array(
                'header'    =>  Mage::helper('tagimage')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('tagimage')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit/special/true'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('tagimage')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('tagimage')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('tagimage_id');
        $this->getMassactionBlock()->setFormFieldName('tagimage');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('tagimage')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('tagimage')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('tagimage/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('tagimage')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'disabled',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('tagimage')->__('Status'),
                         'values' => array(
                                                  0 => 'Enabled',
                                                  1 => 'Disabled',
                                              )
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit/special/true', array('id' => $row->getId()));
  }

}