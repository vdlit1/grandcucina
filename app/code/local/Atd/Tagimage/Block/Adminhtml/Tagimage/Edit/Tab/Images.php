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
class Atd_Tagimage_Block_Adminhtml_Tagimage_Edit_Tab_Images extends Mage_Adminhtml_Block_Widget {

    protected function _prepareForm() {
        $data = $this->getRequest()->getPost();
        $form = new Varien_Data_Form();
        $form->setValues($data);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function __construct() {
        parent::__construct();
        $this->setTemplate('imagestag/tab/image.phtml');
        $this->setId('media_gallery_content');
        $this->setHtmlId('media_gallery_content');
    }

    protected function _prepareLayout() {
        $this->setChild('uploader',
                $this->getLayout()->createBlock('adminhtml/media_uploader')
        );

        $this->getUploader()->getConfig()
                ->setUrl(Mage::getModel('adminhtml/url')->addSessionParam()->getUrl('*/*/image'))
                ->setFileField('image')
                ->setFilters(array(
                'images' => array(
                        'label' => Mage::helper('adminhtml')->__('Images (.gif, .jpg, .png)'),
                        'files' => array('*.gif', '*.jpg','*.jpeg', '*.png')
                )
        ));

        $this->setChild(
                'delete_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->addData(array(
                'id'      => '{{id}}-delete',
                'class'   => 'delete',
                'type'    => 'button',
                'label'   => Mage::helper('adminhtml')->__('Remove'),
                'onclick' => $this->getJsObjectName() . '.removeFile(\'{{fileId}}\')'
                ))
        );

        return parent::_prepareLayout();
    }

    /**
     * Retrive uploader block
     *
     * @return Mage_Adminhtml_Block_Media_Uploader
     */
    public function getUploader() {
        return $this->getChild('uploader');
    }

    /**
     * Retrive uploader block html
     *
     * @return string
     */
    public function getUploaderHtml() {
        return $this->getChildHtml('uploader');
    }

    public function getJsObjectName() {
        return $this->getHtmlId() . 'JsObject';
    }

    public function getAddImagesButton() {
        return $this->getButtonHtml(
                Mage::helper('catalog')->__('Add New Images'),
                $this->getJsObjectName() . '.showUploader()',
                'add',
                $this->getHtmlId() . '_add_images_button'
        );
    }
    public function gettagsColletion(){
             $_model =Mage::getModel('tagimage/tagimage');
             $collection  = $_model->getCollection();
            return $collection;
            
    }
    public function getImagesJson() {
        $_model = Mage::registry('tags_data');
       $speacial = $this->getRequest()->getParam('special');
       
       $_data=null;
        if($_model!=NULL){
             $_data = $_model->getImage();
        }else{
            $_data  = Mage::getSingleton('adminhtml/session')->getImagesData();
        }
          $_result = array();
        if (is_array($_data) and sizeof($_data) > 0) {
            foreach ($_data as &$_item) {
                foreach($this->gettagsColletion() as $_tags){
                 if($_tags->getFile()===$_item['file']){
                    $_result[] = array(
                        'value_id'  =>$_tags->getId(),
                        'url'            => Mage::getSingleton('tagimage/config')->getBaseMediaUrl() . $_item['file'],
                        'file'          => $_item['file'],
                        'label'        => $_item['label'],
                        'position'  => $_item['position'],
                        'disabled'  => $_item['disabled']);
                    }
                }
         }
        }
        if($speacial==true){
                $_rs =array();
                $id = $this->getRequest()->getParam('id');
                $_item = $this->getJsonEdit($id);
                $_rs[] = array(
                        'value_id'  =>$_item->getId(),
                        'url'            => Mage::getSingleton('tagimage/config')->getBaseMediaUrl() . $_item->getFile(),
                        'file'          => $_item->getFile(),
                        'label'        => $_item->getLabel(),
                        'position'  => $_item->getPosition(),
                        'disabled'  => $_item->getDisabled()
                 );

                 return Zend_Json::encode($_rs);
            }
          return Zend_Json::encode($_result);
          
        return '[]';
    }

    public function getImagesValuesJson() {
        $values = array();

        return Zend_Json::encode($values);
    }

    public function getJsonEdit($id){
        $model  = Mage::getModel('tagimage/tagimage')->load($id);
        return $model;
    }
    
    public function getMediaAttributes() {

    }

    public function getImageTypes() {
        $type = array();
        $type['gallery']['label'] = "tagimage";
        $type['gallery']['field'] = "tagimage";

        $imageTypes = array();

        return $type;
    }

    public function getImageTypesJson() {
        return Zend_Json::encode($this->getImageTypes());
    }

    public function getCustomRemove() {
        return $this->setChild(
                'delete_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->addData(array(
                'id'      => '{{id}}-delete',
                'class'   => 'delete',
                'type'    => 'button',
                'label'   => Mage::helper('adminhtml')->__('Remove'),
                'onclick' => $this->getJsObjectName() . '.removeFile(\'{{fileId}}\')'
                ))
        );
    }

    public function getDeleteButtonHtml() {
        return $this->getChildHtml('delete_button');
    }

    public function getCustomValueId() {
        return $this->setChild(
                'value_id',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->addData(array(
                'id'      => '{{id}}-value',
                'class'   => 'value_id',
                'type'    => 'text',
                'label'   => Mage::helper('adminhtml')->__('ValueId'),
                ))
        );
    }

    public function getValueIdHtml() {
        return $this->getChildHtml('value_id');
    }
}