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
class Atd_Tagimage_Block_Adminhtml_Tagimage_Edit_Tab_page  extends Mage_Adminhtml_Block_Widget_Form {
    
 protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('tagimage_form', array('legend'=>Mage::helper('tagimage')->__('Choose Pages')));
        $fieldset->addField('pages', 'selected', array(
            'label'     => Mage::helper('tagimage')->__('Visible In'),
            'required'  => false,
            'name'      => 'pages[]',
            'values'    =>Mage::getSingleton('tagimage/config_source_page')->toOptionArray(),
        ));

        return parent::_prepareForm();
    }

}