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
class Atd_Tagimage_Block_Adminhtml_Tagimage_Edit_Tab_Content extends Mage_Adminhtml_Block_Widget_Form {
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('imagestag/tab/content.phtml');
    }

 protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('tagimage_form', array('legend'=>Mage::helper('tagimage')->__('Conent ')));
       
        $fieldset->addField('tablabel', 'text', array(
                'label'     => Mage::helper('tagimage')->__('Tab label'),
                'name'      => 'tablabel',
            ));
            $fieldset->addField('content', 'text', array(
                'label'     => Mage::helper('tagimage')->__('Tab content'),
                'name'      => 'content',
            ));
            $fieldset->addField('imagecontent', 'editor', array(
                'label'     => Mage::helper('tagimage')->__('Image Content HTML'),
                'required'  => false,
                'name'      => 'imagecontent',
                'width '    =>'500px',  
            ));

        return parent::_prepareForm();
    }
    
   public function getRecord(){
       $id = $this->getRequest()->getParam('id');
       if($id){
           return Mage::getModel('tagimage/tagimage')->load($id);
       }else{
           return Mage::getModel('tagimage/tagimage')->load(0);
       }
       
     }
}