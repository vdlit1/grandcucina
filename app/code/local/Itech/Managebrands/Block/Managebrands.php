<?php
class Itech_Managebrands_Block_Managebrands extends Mage_Core_Block_Template
{
	public function getBrandCollection(){
		$collection = Mage::getModel('managebrands/managebrands')
			->getCollection();
		return $collectionn;
	}
	
	public function getBrand(){
		return Mage::getModel('managebrands/managebrands')->load($this->getRequest()->getParam('id'));
	}
	
	public function getBrandLogoUrl($imageName){
		return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'brands/'.$imageName;
	}
	
	public function ignorCategoryIds(){
		$arr = array();
		$helper = Mage::helper('catalog/category');
		$categories = $helper->getStoreCategories();
		foreach($categories as $category){
			$arr[] = $category->getId();
		}
		return $arr;
	}
	
	public function getProduct(){
		$collection = Mage::getResourceModel('catalog/product_collection')
			->addAttributeToFilter('grandcucina_brands', $this->getBrand()->getOptionId())
			->addAttributeToFilter('status', 1)
			->addAttributeToFilter('visibility', 4);
		return $collection;
	}
	
	public function getProductUse(){
		return $this->getProduct()->addAttributeToSelect('*')->addAttributeToFilter('used_by', array('neq' => ''));
	}
	
	public function getCategoriesBrand(){
		$collection = $this->getProduct();
		$arrCategoryIds = array();
		if(count($collection)>0){
			foreach($collection as $product){
				$categoryIds = $product->getCategoryIds();
				if(count($categoryIds)>0){
					foreach($categoryIds as $id){
						$arrCategoryIds[] = $id;
					}
				}
			}
		}
		$arrCategoryIds = array_unique($arrCategoryIds);
		$arrIgnor = $this->ignorCategoryIds();
		$result = array();
		if(count($arrCategoryIds)>0){
			foreach($arrCategoryIds as $catId){
				if(!in_array($catId, $arrIgnor)){
					$result[] = $catId;
				}
			}
		}
		return $result;
	}
	
	public function getCategoryUrl($category)
    {
        if ($category instanceof Mage_Catalog_Model_Category) {
            $url = $category->getUrl();
        } else {
            $url = Mage::getModel('catalog/category')
                ->setData($category->getData())
                ->getUrl();
        }

        return $url;
    }
}