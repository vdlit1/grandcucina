<?php
/**
 * Taxcodes Resource
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * @category   Taxcode
 * @package    Slink
 * @copyright  Copyright (c) 2012 Saaslink
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Bernard Tai bernard@saaslink.com
 */

class Slink_MageSaasu_Model_Mysql4_Taxcodes extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('slink/slink_tax_code', 'id');
    }
}