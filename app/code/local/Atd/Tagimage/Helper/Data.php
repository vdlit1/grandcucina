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
class Atd_Tagimage_Helper_Data extends Mage_Core_Helper_Abstract
{
    /*
     * Get image url of a banner
     */
    public function getImageUrl($url = null) {
        return Mage::getSingleton('tagimage/config')->getBaseMediaUrl() . $url;
    }

    /**
     * Encode the mixed $valueToEncode into the JSON format
     *
     * @param mixed $valueToEncode
     * @param  boolean $cycleCheck Optional; whether or not to check for object recursion; off by default
     * @param  array $options Additional options used during encoding
     * @return string
     */
    public function jsonEncode($valueToEncode, $cycleCheck = false, $options = array())
    {
        $json = Zend_Json::encode($valueToEncode, $cycleCheck, $options);
        /* @var $inline Mage_Core_Model_Translate_Inline */
        $inline = Mage::getSingleton('core/translate_inline');
        if ($inline->isAllowed()) {
            $inline->setIsJson(true);
            $inline->processResponseBody($json);
            $inline->setIsJson(false);
        }

        return $json;
    }

    public function settings($setting){
        return Mage::getStoreConfig('tags/general/'.$setting);
    }

    public function settingAttribute($setting){
          return Mage::getStoreConfig('tags/frontend/'.$setting);
    }
    
    public function getWithFrame(){
        $with = $this->settings('setwidth');
        return $with.'px';
    }

    public function getHeightFrame(){
        $height = $this->settings('setheight');
        return $height.'px';
    }

}