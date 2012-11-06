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
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml system config array field renderer
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Slink_Magesaasu_Block_Admin_Config_Regexceptions extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('regexp', array('label' => Mage::helper('adminhtml')->__('Currency'),
                                         'class'=>'required-entry',
                                         'style' => 'width:120px',
        ));
        $this->addColumn('value', array('label' => Mage::helper('adminhtml')->__('Account'),
                                        'class'=>'required-entry',
                                        'style' => 'width:120px',
                                        'renderer' => Mage::app()->getLayout()->createBlock('slink/admin_config_renderer_bankaccounts')
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add Exception');
        parent::__construct();
    }
    protected function _renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new Exception('Wrong column name specified.');
        }
        $column     = $this->_columns[$columnName];
        $inputName  = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';
        
        if ($column['renderer']) {
            return $column['renderer']->setInputName($inputName)->setColumnName($columnName)->setColumn($column)
            ->toHtml();
        }
        
        return '<input id="#{'.$columnName.'}"  '.'type="text" name="' . $inputName . '" value="#{' . $columnName . '}" ' .
        ($column['size'] ? 'size="' . $column['size'] . '"' : '') . ' class="' .
        (isset($column['class']) ? $column['class'] : 'input-text') . '"'.
        (isset($column['style']) ? ' style="'.$column['style'] . '"' : '') . '/>';
    }
    
    public function _toHtml(){
        return '<input type="hidden" id="'.$this->getElement()->getHtmlId().'"/>'.parent::_toHtml();
    }
}
