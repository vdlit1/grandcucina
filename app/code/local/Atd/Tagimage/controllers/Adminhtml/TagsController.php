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
 * 
 
 */
class  Atd_Tagimage_Adminhtml_TagsController extends Mage_Adminhtml_Controller_Action {
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('tagimage');

        return $this;
    }


    public function indexAction() {
         $this->_title($this->__('Images Tags'));
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('tagimage/adminhtml_gallery'));
        $this->renderLayout();
    }

    public function tagsAction(){
        $this->_title($this->__('Catalog Images Tags'));
        Mage::getSingleton('core/session')->setEntityId($this->getRequest()->getParam('id'));
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('tagimage/adminhtml_tags'));
        $this->renderLayout();
    }
    public function savetagsAction(){
        $data = $this->getRequest()->getPost();
        $model = Mage::getModel('tagimage/tags');
        $model ->setData($data);
        $model->save();
    }
    public function deleteAction(){
        $model = Mage::getModel('tagimage/tags');
        $id = $this->getRequest()->getParam('tags_id');
        $model ->setId($id);
        $model->delete();
    }

    public function editAction(){
        $this->_title($this->__('Images Tags'));
        Mage::getSingleton('core/session')->setEntityId($this->getRequest()->getParam('id'));
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('tagimage/adminhtml_edittag'));
        $this->renderLayout();
    }

    public function loadProductAction(){
        $block =   $this->getLayout()->createBlock('tagimage/adminhtml_product_grid')->toHtml();
        $this->getResponse()->setBody(Mage::helper('core')->__($block));
    }
    public function saveeditAction(){
        $data = $this->getRequest()->getPost();
        $model = Mage::getModel('tagimage/tags');
        $model ->setData($data)->setId($data['tag_id']);
        $model->save();
    }

 }