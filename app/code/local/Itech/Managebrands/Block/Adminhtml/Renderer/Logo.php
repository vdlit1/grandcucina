<?php
/**
 * Grid column renderer
 *
 * @category    Halo
 * @package     Halo_Autopacks
 * @author      Haloweb team
 */
class Itech_Managebrands_Block_Adminhtml_Renderer_Logo extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Render column
     *
     * @param Varien_Object $row
     * @return string html
     */
    public function render(Varien_Object $row)
    {
    	$imageName = $row->getFilename();
        $imageUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'brands/'.$imageName;
    	return '<img src="'.$imageUrl.'" alt="" width="100" />';
    }
}

?>