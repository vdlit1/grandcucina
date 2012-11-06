<?php
/*
 *  Created on Mar 16, 2011
 *  Author Ivan Proskuryakov - volgodark@gmail.com - Magazento.com
 *  Copyright Proskuryakov Ivan. Magazento.com © 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php class Magazento_Megamenu_Helper_Data extends Mage_Core_Helper_Abstract {

    public function versionUseAdminTitle() {
        $info = explode('.', Mage::getVersion());
        if ($info[0] > 1) {
            return true;
        }
        if ($info[1] > 3) {
            return true;
        }
        return false;
    }

    public function versionUseWysiwig() {
        $info = explode('.', Mage::getVersion());
        if ($info[0] > 1) {
            return true;
        }
        if ($info[1] > 3) {
            return true;
        }
        return false;
    }

    public function numberArray($max,$text) {

        $items = array();
        for ($index = 1; $index <= $max; $index++) {
            $items[$index]=$text.' '.$index;
        }
        return $items;
    }

    public function getSubCategories($parentId, $sorted=false, $asCollection=false, $toLoad=true)
    {
        $category = Mage::getModel('catalog/category');
        /* @var $category Mage_Catalog_Model_Category */
        if (!$category->checkId($parentId)) {
            if ($asCollection) {
                return new Varien_Data_Collection();
            }
            return array();
        }

        $tree = $category->getTreeModel();
        /* @var $tree Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Tree */
        $nodes = $tree->loadNode($parentId)
            ->loadChildren()
            ->getChildren();

        $tree->addCollectionData(null, $sorted, $parentId, $toLoad, true);

        if ($asCollection) {
            return $tree->getCollection();
        } else {
            return $nodes;
        }
    }
}
