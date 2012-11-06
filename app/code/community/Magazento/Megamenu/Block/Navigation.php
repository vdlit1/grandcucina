<?php
/*
 *  Created on Mar 16, 2011
 *  Author Ivan Proskuryakov - volgodark@gmail.com - Magazento.com
 *  Copyright Proskuryakov Ivan. Magazento.com © 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php
class Magazento_Megamenu_Block_Navigation extends Mage_Catalog_Block_Navigation {
    
    protected function _prepareLayout()
    {
        $skin=Mage::getStoreConfig('megamenu/options/menuskin').'.css';
        if ($skin=='menured.css') $this->getLayout()->getBlock('head')->addCss('magazento/megamenu/'.$skin);
        if ($skin=='menuredtabs.css') $this->getLayout()->getBlock('head')->addCss('magazento/megamenu/'.$skin);
        if ($skin=='menublue.css') $this->getLayout()->getBlock('head')->addCss('magazento/megamenu/'.$skin);
        if ($skin=='menugreen.css') $this->getLayout()->getBlock('head')->addCss('magazento/megamenu/'.$skin);		
        return parent::_prepareLayout();
    }

    public function drawItem($category=0,$item,$class) {
        $userows =Mage::getStoreConfig('megamenu/options/userows');
        $url=$this->getCategoryUrl($category);
        if ($item['url']!='') $url=$item['url'];

        $html = '<li class="drop menu_'.$item['align_category'].' '.$class.'">';
        $html.= '<a class="drop" href="' . $url . '">' . $this->htmlEscape($item['title']) . '</a>' . "\n";
            $activeChildren = $this->getActiveChildren($category);
            if (sizeof($activeChildren) > 0) {
                if (!$userows) $html .= $this->drawColumns($activeChildren,$item);
                if ($userows)  $html .= $this->drawColumnsRows($activeChildren,$item);
            }
        $html .= "</li>";

        return $html;
    }
    
    public function drawCatalog() {

        $html='';
        $data = Mage::getModel('megamenu/data')->getCatalog();
        $i=0;
        foreach ($data as $key => $item) {
            $i++;
            $categoryId = $item['catalog_id'];
            $categoryObject = Mage::getModel('catalog/category')->load($categoryId);
            $categoryParentId = $categoryObject->getParentId();
            foreach (Mage::helper('megamenu/data')->getSubCategories($categoryParentId) as $_category) {
                if ($_category->getId() == $categoryId) {
                    $class = 'category'.$categoryId;
                    if ($i == 1) $class.= ' first';
                    if ($i == (count($data))) $class.= ' last';
                    $html.=$this->drawItem($_category,$item,$class);
                }
            }

        }


        return $html;

    }


    public function array_chunk_fixed($input, $num, $preserve_keys = FALSE) {
        $count = count($input) ;
        if($count)
            $input = array_chunk($input, ceil($count/$num), $preserve_keys) ;
        $input = array_pad($input, $num, array()) ;
        return $input ;
    }
    
    public function drawColumnsRows($children,$item) {
        
        $col=$item['column'];
        $rows = count($children) / $col;
        $chunks = $this->array_chunk_fixed($children, $rows);
		$helper = Mage::helper('cms');
		$processor = $helper->getPageTemplateProcessor();
		
        $html = '';
        $html .= '<div class="dropdown_'.$col.'column align_'.$item['align_content'].' ">';
            $html .= '<div class="col_'.$col.'"><div class="content_top">';
			$html .= $processor->filter($item['content_top']);     
            $html .= '</div></div>';
                foreach ($chunks as $chunk) {
                    $html .= '<div class="col_'.$col.'">';
                        $items = $this->array_chunk_fixed($chunk, $col);
                        foreach ($items as $value) {
                            $html .= '<div class="col_1">';
                            $html .= $this->drawNestedMenus($value, 1);
                            //$html .= 'key';
                            $html .= '</div>';
                        }
                    //$html .= 'row';
                    $html .= '</div>';
                }

            $html .= '<div class="col_'.$col.'"><div class="content_bottom">';
			$html .= $processor->filter($item['content_bottom']);  
            $html .= '</div></div>';
        $html .= '</div>';
        return $html;

    }

    public function drawColumns($children,$item) {
        $col=$item['column'];
        $html = '';
        $chunks = $this->array_chunk_fixed($children, $col);
        $html .= '<div class="dropdown_'.$col.'column align_'.$item['align_content'].' ">';
		$helper = Mage::helper('cms');
		$processor = $helper->getPageTemplateProcessor();
		
		
            $html .= '<div class="col_'.$col.'"><div class="content_top">';
			$html .= $processor->filter($item['content_top']);       
            $html .= '</div></div>';

                foreach ($chunks as $key => $value) {
                    $html .= '<div class="col_1">';
                    $html .= $this->drawNestedMenus($value, 1);
                    $html .= '</div>';
                }

            $html .= '<div class="col_'.$col.'"><div class="content_bottom">';
			$html .= $processor->filter($item['content_bottom']);   
            $html .= '</div></div>';

        $html .= '</div>';
        return $html;
    }

    public function drawNestedMenus($children, $level=1,$morehref ='') {
        $moretext=Mage::getStoreConfig('megamenu/options/moretext');
        $maxsubcatnum=Mage::getStoreConfig('megamenu/options/maximumsubcat');

        $html = '<ul>';
        $i=0;
        foreach ($children as $child) {
            if ($child->getIsActive()) {
                
                $html .= '<li class="level' . $level . '">';
                $html .= '<a href="' . $this->getCategoryUrl($child) . '"><span class="level' . $level . '">' . $this->htmlEscape($child->getName()) . '</span></a>';
                $activeChildren = $this->getActiveChildren($child);
                if (sizeof($activeChildren) > 0) {
                    $html .= $this->drawNestedMenus($activeChildren, $level + 1,$this->getCategoryUrl($child));
                }
                $i++;
                $html .= '</li>';
                if ($i==$maxsubcatnum) {
                    $html .= '<li class="level' . $level . '">';
                    $html .= '<a href="'.$morehref.'"><span class="viewall level' . $level . '">'.$moretext.'</span></a>';
                    $html .= '</li>';
                    break;
                }
            }
        }
        $html .= '</ul>';
        return $html;
    }

    protected function getActiveChildren($parent) {
        $activeChildren = array();
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = $parent->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $parent->getChildren();
            $childrenCount = count($children);
        }
        $hasChildren = $children && $childrenCount;
        if ($hasChildren) {
            foreach ($children as $child) {
                if ($child->getIsActive()) {
                    array_push($activeChildren, $child);
                }
            }
        }
        return $activeChildren;
    }

    public function getCategoryPath($category) {
        $url = '';
        if ($category instanceof Mage_Catalog_Model_Category) {
            $url = $category->getPathInStore();
            $url = strtr($url, ".", "-");
            $url = strtr($url, "/", "-");
        } else {
            // do nothing
        }
        return $url;
    }

// -----------------------------------------------------------------------------
    public function drawAllCategoriesMenu() {
        $html = '';
        $collection = $this->getStoreCategories();
        foreach ($collection as $node) {
            if ($node->getData('level') == 2) {
                $html .= '<li>';
                $html .= '<a href="' . $this->getCategoryUrl($node). '">' . $node->getData('name') . '</a>';
                $html .= '</li>';
            }
        }
        return $html;
    }
    public function drawAdminMenu() {
        $storeId  = Mage::app()->getStore()->getId();
        $storeUrl = Mage::getModel('core/store')->load($storeId)->getUrl();
        $storeUrl.='#';
        $class = '';
        $data=Mage::getModel('megamenu/data')->getItems();
        $html='';
        $i=0;
		$helper = Mage::helper('cms');
		$processor = $helper->getPageTemplateProcessor();
                $menu =0 ;
        foreach ($data as $item) {
                $menu++;
                $i++;
                if ($i == (count($data))) $class= ' last';
				
                $url=$storeUrl;
                if ($item['url']!='') $url=$item['url'];
                    if($i==1){
                            $html .= '<li class="drop menu_'.$item['align_item'].' first">';
                    }
                    else{
                            $html .= '<li class="drop menu_'.$item['align_item'].' '. $class .'">';
                    }
                $html .= '<a href="'.$url.'" class="drop navlink">'.$item['title'].'</a>';
                $html .= '<div class="dropdowns dropdown_'.$item['column'].'column align_'.$item['align_content'].'">';
                $html .= '<div class="col_'.$item['column'].'">';
				$html .= $processor->filter($item['content']);   
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</li>';
        }
        return $html;
    }
	
	public function spilitArray($sum){
		if($sum%2==0){
			$col1 = $sum/2;
		}
		else{
			$col1 = floor($sum/2)+1;
		}
		if($sum==1){
			$col1 = 1;
		}
		return $col1;
	}
	
	public function spilitCategory($children, $sum){
		$result = $arr = $arr1 = $arr2 = array();
		foreach($children as $_category){
			$arr[] = $_category->getId();
		}
		
		for($i=0; $i<$this->spilitArray($sum); $i++){
			$arr1[] = $arr[$i];
		}
		$result[] = $arr1;
		for($j=$this->spilitArray($sum); $j<$sum; $j++){
			$arr2[] = $arr[$j];
		}
		$result[] = $arr2;
		
		return $result;
	}

}