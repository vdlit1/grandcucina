<?php

class Atd_Tagimage_Block_Adminhtml_Tagimage_Edit_Images extends Mage_Adminhtml_Block_Widget_Grid
{
   public function __construct()
  {
      parent::__construct();
      $this->setTemplate('imagestag/list/grid.phtml');
      $this->setId('tagimageGrid');
      $this->setDefaultSort('tagimage_id');
      $this->setDefaultDir('ASC');
      $this->setUseAjax(true);
      $this->setSaveParametersInSession(true);
  }

  public function setAddButtonHtml(){
       $url = "'".Mage::getUrl('tagimage/adminhtml_tagimage/new')."key/".Mage::getSingleton('adminhtml/url')->getSecretKey("adminhtml_tagimage","new")."'";
        return '<button style="" onclick="setLocation('.$url.')" class="scalable add" type="button"><span>Add Images</span></button>';
  }
  
  public function getMainButtonsHtml(){
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();
            $html.= $this->setAddButtonHtml();
        }
        return $html;
  }
  
  public function getAllCateory($cateStore){
      $_cateId  = array();
       $collection = Mage::getModel('tagimage/tagimage')->getCollection();
       foreach ($collection as $category){
           $_cate = explode(',',$category['categories']);
           foreach($_cate as $cate){
              if($cateStore==$cate){
                    $_cateId[] = $category['tagimage_id'];
              }
           }
       }
       return $_cateId;
  }
  protected function _prepareCollection()
  {
      $id = $this->getRequest()->getParam('Node');
      $collection = Mage::getModel('tagimage/tagimage')->getCollection();
      if(!empty($id)){
      $collection ->  addCategoryFilter($this->getAllCateory($id));
      }
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


	$this->addColumn('tags',
            array(
                'header'    =>  Mage::helper('tagimage')->__('Tags'),
                'width'     => '80',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('tagimage')->__('Click to tag'),
                        'url'       => array('base'=> 'tagimage/adminhtml_tags/tags'),
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

    public function getCategoryId(){
        if($data = $this->getRequest()->getPost()){
            return $data['Node'];
        }
        return '';
    }
    public function getCategory(){
            $source = '{{block type="tagimage/tagimage" setCategoryId="'.$this->getCategoryId().'" name="tagimage_tagimage_top" template="tagimage/top.phtml"}}';
            return $source;
    }
}