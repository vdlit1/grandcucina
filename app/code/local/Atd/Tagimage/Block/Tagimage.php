<?php
class Atd_Tagimage_Block_Tagimage extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     protected $_isActive = 1;


    public function getCollection() {
       
        if ($this->_collection) {
            return $this->_collection;
        }
		$storeId = Mage::app()->getStore()->getId();
                $this->_collection = Mage::getModel('tagimage/tagimage')->getCollection()
                ->addFieldToFilter('disabled', 0);
              //  ->addFieldToFilter('categories', array('in'=>$category));
		return $this->_collection;
    }
    
    public function getCurrentPageId(){
            $dataCurrentPage = $this->getHelper('cms/page')->getPage()->getData();
         if ($dataCurrentPage) {
                $identifierCurrentPage = $dataCurrentPage['page_id'];
                return $identifierCurrentPage;
          }
          return false;
    }

    public function getCollectionPage(){
        $collection = Mage::getModel('tagimage/page')->getCollection();
        $tagList = array();
        foreach($collection as $Page){
          $_page =  explode(',',$Page->getPage());
          if(in_array($this->getCurrentPageId(),$_page)){
                $tagList[] = $Page->getId();
          }
        }
        return $tagList;
    }
     public function getOverlayHtml($image_id){
         $write = Mage::getSingleton('core/resource')->getConnection('core_write');
         $reslute =    $write->query("select * from tags where image_id=$image_id");
         $html ='';
         $_helper = Mage::helper('catalog/output');
         $_tagHelper = Mage::helper('tagimage');
         $productUrl = '';
         $symbol = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
        foreach($reslute as $key => $tags){
      
                $productUrl =$this->getProductUrl($tags['sku']);
                $top = $tags['top']-15;
                $left= $tags['left']-15;
                $_product = Mage::getModel('catalog/product')->loadByAttribute('sku',  $tags['sku']);
				$_product = Mage::getModel('catalog/product')->load($_product->getId()); 
                $price = explode('.',$_product->getFinalPrice());
                $subPrice = $price[1]; 
                if(strlen($price[1])>2) {
                    $subPrice= substr($price[1],0,2);
                }
                $_div_left =$tags['left'];
                $html.= '<a id="link-'.$tags['sku'].'" href="'.$this->getProductUrl($tags['sku']).'"><span lang="tags_'.$key.'_'.$tags['image_id'].$tags['width'].'" onmouseover="showTag(this.lang)" onmouseout="hideTag(this.lang)" class="jTagRead" style="top:'.$top.'px; left:'.$left.'px; opacity:1;"><span  class="circle">&nbsp;</span></span></a>';
                $html.= '<a href="'.$productUrl.'"><div style="top:'.((float)$tags['top']-15).'px; left:'.((float)$_div_left-15).'px; opacity:1;" id="tags_'.$key.'_'.$tags['image_id'].$tags['width'].'" class="jTagTag" onmouseover="showTag(this.id)" onmouseout="hideTag(this.id)" >';
				$html.= '<table class="references-container table_tags_'.$key.'_'.$tags['image_id'].$tags['width'].'" id="table_tags_'.$key.'_'.$tags['image_id'].$tags['width'].'" style="display:none">';
					$html.= '<tr class="references-view-container view-information">';
					$html.= '<td class="references-view-midel" title="'.$tags['sku'].'">
                                                <div class="product-infomation">
                                                     <h2>'.$_product->getAttributeText('grandcucina_brands').'</h2>
                                                     <a style="text-decoration:none" href="'.$this->getProductUrl($tags['sku']).'">'.Mage::helper('core/string')->truncate($_product->getName(),30).'</a> 
                                                 </div>
                                                     <p class="price"><span>'.$symbol.'</span>'.$price[0].'<span>.'.$subPrice.'</span></p>
                                                </td>';   
                                        $html.= '<td class="references-view-right"></td>';
					$html.= '</tr>';
                               $html.= '</table>';

                $html.= '</div></a>';
            }
               return $html;
    }

    public function getProductUrl($sku){
        if($sku){
            $_product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);
            return $_product->getProductUrl();
        }
    }
    public function getCurrentCategory(){
        return    $this->getData('setCategoryId');
    }
} 