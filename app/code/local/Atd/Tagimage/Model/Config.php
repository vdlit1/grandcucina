<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.

 */
class Atd_Tagimage_Model_Config extends Mage_Catalog_Model_Product_Media_Config {

    public function getBaseMediaPath() {
        return Mage::getBaseDir('media') .DS. 'tagimage';
    }

    public function getBaseMediaUrl() {
        return Mage::getBaseUrl('media') . 'tagimage';
    }

    public function getBaseTmpMediaPath() {
        return Mage::getBaseDir('media') .DS. 'tmp' .DS. 'tagimage';
    }

    public function getBaseTmpMediaUrl() {
        return Mage::getBaseUrl('media') . 'tmp/tagimage';
    }

    public function getHeight(){
         $height =(int) Mage::getStoreConfig('tags/general/setheight');
         if(empty($height)){
             $height =440;
         }
         return $height;
    }

    public function getWidht(){
         $width = (int) Mage::getStoreConfig('tags/general/setwidth');
         if(empty($width)){
             $width =810;
         }
          return $width;
    }
}