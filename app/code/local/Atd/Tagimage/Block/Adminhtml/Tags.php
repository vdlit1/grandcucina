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
 *
 */
class Atd_Tagimage_Block_Adminhtml_Tags extends Mage_Adminhtml_Block_Widget_Grid_Container {
    public function __construct() {
        
        $this->_controller = 'adminhtml_tagimage';
        $this->_blockGroup = 'tagimage';
        $this->_headerText = Mage::helper('tagimage')->__('Category Images Tags');
        parent::__construct();

        $this->setTemplate('imagestag/tags.phtml');
    }

    protected function _prepareLayout() {
        $this->setChild('add_new_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                'label'     => Mage::helper('tagimage')->__('Add category Tags'),
                'onclick'   => "setLocation('".$this->getUrl('*/*/add')."')",
                'class'   => 'add'
                ))
        );
        /**
         * Display store switcher if system has more one store
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $this->setChild('store_switcher',
                    $this->getLayout()->createBlock('adminhtml/store_switcher')
                    ->setUseConfirm(false)
                    ->setSwitchUrl($this->getUrl('*/*/*', array('store'=>null)))
            );
        }
       
        return parent::_prepareLayout();
    }

    public function getAddNewButtonHtml() {
        return $this->getChildHtml('add_new_button');
    }

    public function getGridHtml() {
        return $this->getChildHtml('category_grid');
    }

    public function getStoreSwitcherHtml() {
        return $this->getChildHtml('store_switcher');
    }
    public function getProductCollection(){
        return  $this->getLayout()->createBlock('tagimage/adminhtml_product_grid')->toHtml();
    }
    public function getImageProcess($id){
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
         $reslute =    $write->query("select file from tagimage where tagimage_id=$id");
        return $reslute;
    }
    
     public function getOverlayHtml($image_id){
         $write = Mage::getSingleton('core/resource')->getConnection('core_write');
         $reslute =    $write->query("select * from tags where image_id=$image_id");
         $html ='';
            foreach($reslute as $tags){
                $html.= '<div style="width:'.$tags['width'].'px; height:'.$tags['height'].'px; top:'.$tags['top'].'px; left:'.$tags['left'].'px; opacity: 1;" id="'.$tags['image_id'].'" class="jTagTag">';
                $html.='<span>'.$tags['label'].'</span>';
                        $html.='<div style="width: 100%; height: 100%;">';
                        $html.='<div class="jTagDeleteTag" style="display: none;">';
                        $html.='</div>';
                     $html.='</div>';
                $html.='</div>';
            }
        return $html;
    }
}