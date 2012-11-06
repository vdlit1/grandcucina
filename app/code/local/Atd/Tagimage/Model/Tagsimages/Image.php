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
class Atd_Tagimage_Model_Tagsimages_Image extends Mage_Core_Model_Abstract
{
    protected $_width;
    protected $_height;
    protected $_quality = 80;

    protected $_keepAspectRatio  = true;
    protected $_keepFrame        = true;
    protected $_keepTransparency = true;
    protected $_constrainOnly    = false;
    protected $_backgroundColor  = array(255, 255, 255);

    protected $_baseFile;
    protected $_isBaseFilePlaceholder;
    protected $_newFile;
    protected $_processor;
    protected $_destinationSubdir;
    protected $_angle;

    protected $_watermarkFile;
    protected $_watermarkPosition;
    protected $_watermarkWidth;
    protected $_watermarkHeigth;
    protected $_watermarkImageOpacity = 70;

  
    protected function _construct()
    {
        $this->_init('tagimage/tagsimage_image');
    }

    
    public function setWidth($width)
    {
        $this->_width = $width;
        return $this;
    }

    public function getWidth()
    {
        return $this->_width;
    }

   
    public function setHeight($height)
    {
        $this->_height = $height;
        return $this;
    }

    public function getHeight()
    {
        return $this->_height;
    }

   
    public function setQuality($quality)
    {
        $this->_quality = $quality;
        return $this;
    }

    
    public function getQuality()
    {
        return $this->_quality;
    }

 
    public function setKeepAspectRatio($keep)
    {
        $this->_keepAspectRatio = (bool)$keep;
        return $this;
    }

    
    public function setKeepFrame($keep)
    {
        $this->_keepFrame = (bool)$keep;
        return $this;
    }

    /**
     * @return My_Ibanner_Model_Banner_Image
     */
    public function setKeepTransparency($keep)
    {
        $this->_keepTransparency = (bool)$keep;
        return $this;
    }

    /**
     * @return My_Ibanner_Model_Banner_Image
     */
    public function setConstrainOnly($flag)
    {
        $this->_constrainOnly = (bool)$flag;
        return $this;
    }

    /**
     * @return My_Ibanner_Model_Banner_Image
     */
    public function setBackgroundColor(array $rgbArray)
    {
        $this->_backgroundColor = $rgbArray;
        return $this;
    }

    /**
     * @return My_Ibanner_Model_Banner_Image
     */
    public function setSize($size)
    {
        // determine width and height from string
        list($width, $height) = explode('x', strtolower($size), 2);
        foreach (array('width', 'height') as $wh) {
            $$wh  = (int)$$wh;
            if (empty($$wh))
                $$wh = null;
        }

        // set sizes
        $this->setWidth($width)->setHeight($height);

        return $this;
    }

    protected function _checkMemory($file = null)
    {


        return $this->_getMemoryLimit() > ($this->_getMemoryUsage() + $this->_getNeedMemoryForFile($file)) || $this->_getMemoryLimit() == -1;
    }

    protected function _getMemoryLimit()
    {
        $memoryLimit = trim(strtoupper(ini_get('memory_limit')));

        if (!isSet($memoryLimit[0])){
            $memoryLimit = "128M";
        }

        if (substr($memoryLimit, -1) == 'K') {
            return substr($memoryLimit, 0, -1) * 1024;
        }
        if (substr($memoryLimit, -1) == 'M') {
            return substr($memoryLimit, 0, -1) * 1024 * 1024;
        }
        if (substr($memoryLimit, -1) == 'G') {
            return substr($memoryLimit, 0, -1) * 1024 * 1024 * 1024;
        }
        return $memoryLimit;
    }

    protected function _getMemoryUsage()
    {
        if (function_exists('memory_get_usage')) {
            return memory_get_usage();
        }
        return 0;
    }

    protected function _getNeedMemoryForFile($file = null)
    {
        $file = is_null($file) ? $this->getBaseFile() : $file;
        if (!$file) {
            return 0;
        }

        if (!file_exists($file) || !is_file($file)) {
            return 0;
        }

        $imageInfo = getimagesize($file);

        if (!isset($imageInfo[0]) || !isset($imageInfo[1])) {
            return 0;
        }
        if (!isset($imageInfo['channels'])) {
            // if there is no info about this parameter lets set it for maximum
            $imageInfo['channels'] = 4;
        }
        if (!isset($imageInfo['bits'])) {
            // if there is no info about this parameter lets set it for maximum
            $imageInfo['bits'] = 8;
        }
        return round(($imageInfo[0] * $imageInfo[1] * $imageInfo['bits'] * $imageInfo['channels'] / 8 + Pow(2, 16)) * 1.65);
    }

    /**
     * Convert array of 3 items (decimal r, g, b) to string of their hex values
     *
     * @param array $rgbArray
     * @return string
     */
    protected function _rgbToString($rgbArray)
    {
        $result = array();
        foreach ($rgbArray as $value) {
            if (null === $value) {
                $result[] = 'null';
            }
            else {
                $result[] = sprintf('%02s', dechex($value));
            }
        }
        return implode($result);
    }

  
    public function setBaseFile($file)
    {
        $this->_isBaseFilePlaceholder = false;

        if (($file) && (0 !== strpos($file, '/', 0))) {
            $file = '/' . $file;
        }
        $baseDir = Mage::getSingleton('tagimage/config')->getBaseMediaPath();

        if ('/no_selection' == $file) {
            $file = null;
        }
        if ($file) {
            if ((!file_exists($baseDir . $file)) || !$this->_checkMemory($baseDir . $file)) {
                $file = null;
            }
        }
        if (!$file) {
          
            $isConfigPlaceholder = Mage::getStoreConfig("catalog/placeholder/{$this->getDestinationSubdir()}_placeholder");
            $configPlaceholder   = '/placeholder/' . $isConfigPlaceholder;
            if ($isConfigPlaceholder && file_exists($baseDir . $configPlaceholder)) {
                $file = $configPlaceholder;
            }
            else {
                // replace file with skin or default skin placeholder
                $skinBaseDir     = Mage::getDesign()->getSkinBaseDir();
                $skinPlaceholder = "/images/catalog/product/placeholder/{$this->getDestinationSubdir()}.jpg";
                $file = $skinPlaceholder;
                if (file_exists($skinBaseDir . $file)) {
                    $baseDir = $skinBaseDir;
                }
                else {
                    $baseDir = Mage::getDesign()->getSkinBaseDir(array('_theme' => 'default'));
                    if (!file_exists($baseDir . $file)) {
                        $baseDir = Mage::getDesign()->getSkinBaseDir(array('_theme' => 'default', '_package' => 'base'));
                    }
                }
            }
            $this->_isBaseFilePlaceholder = true;
        }

        $baseFile = $baseDir . $file;

        if ((!$file) || (!file_exists($baseFile))) {
            throw new Exception(Mage::helper('catalog')->__('Image file was not found.'));
        }

        $this->_baseFile = $baseFile;

   
        $path = array(
            Mage::getSingleton('tagimage/config')->getBaseMediaPath(),
            'cache',
            Mage::app()->getStore()->getId(),
    
        );
        if((!empty($this->_width)) || (!empty($this->_height)))
            $path[] = "{$this->_width}x{$this->_height}";

   
        $miscParams = array(
                ($this->_keepAspectRatio  ? '' : 'non') . 'proportional',
                ($this->_keepFrame        ? '' : 'no')  . 'frame',
                ($this->_keepTransparency ? '' : 'no')  . 'transparency',
                ($this->_constrainOnly ? 'do' : 'not')  . 'constrainonly',
                $this->_rgbToString($this->_backgroundColor),
                'angle' . $this->_angle,
                'quality' . $this->_quality
        );

        // if has watermark add watermark params to hash
        if ($this->getWatermarkFile()) {
            $miscParams[] = $this->getWatermarkFile();
            $miscParams[] = $this->getWatermarkImageOpacity();
            $miscParams[] = $this->getWatermarkPosition();
            $miscParams[] = $this->getWatermarkWidth();
            $miscParams[] = $this->getWatermarkHeigth();
        }

        $path[] = md5(implode('_', $miscParams));

      
        $this->_newFile = implode('/', $path) . $file; 

        return $this;
    }

    public function getBaseFile()
    {
        return $this->_baseFile;
    }

    public function getNewFile()
    {
        return $this->_newFile;
    }

   
    public function setImageProcessor($processor)
    {
        $this->_processor = $processor;
        return $this;
    }

 
    public function getImageProcessor()
    {
        if( !$this->_processor ) {

            $this->_processor = new Varien_Image($this->getBaseFile());
        }
        $this->_processor->keepAspectRatio($this->_keepAspectRatio);
        $this->_processor->keepFrame($this->_keepFrame);
        $this->_processor->keepTransparency($this->_keepTransparency);
        $this->_processor->constrainOnly($this->_constrainOnly);
        $this->_processor->backgroundColor($this->_backgroundColor);
        #$this->_processor->quality($this->_quality);
        return $this->_processor;
    }

  
    public function resize()
    {
        if (is_null($this->getWidth()) && is_null($this->getHeight())) {
            return $this;
        }
        $this->getImageProcessor()->resize($this->_width, $this->_height);
        return $this;
    }

  
    public function rotate($angle)
    {
        $angle = intval($angle);
        $this->getImageProcessor()->rotate($angle);
        return $this;
    }

  
    public function setAngle($angle)
    {
        $this->_angle = $angle;
        return $this;
    }

    public function setWatermark($file, $position=null, $size=null, $width=null, $heigth=null, $imageOpacity=null)
    {
        if ($this->_isBaseFilePlaceholder)
        {
            return $this;
        }

        if ($file) {
            $this->setWatermarkFile($file);
        } else {
            return $this;
        }

        if ($position)
           $this->setWatermarkPosition($position);
        if ($size)
            $this->setWatermarkSize($size);
        if ($width)
            $this->setWatermarkWidth($width);
        if ($heigth)
            $this->setWatermarkHeigth($heigth);
        if ($imageOpacity)
            $this->setImageOpacity($imageOpacity);

        $filePath = $this->_getWatermarkFilePath();

        if($filePath) {
            $this->getImageProcessor()
                ->setWatermarkPosition( $this->getWatermarkPosition() )
                ->setWatermarkImageOpacity( $this->getWatermarkImageOpacity() )
                ->setWatermarkWidth( $this->getWatermarkWidth() )
                ->setWatermarkHeigth( $this->getWatermarkHeigth() )
                ->watermark($filePath);
        }

        return $this;
    }

    
    public function saveFile()
    {
        $this->getImageProcessor()->save($this->getNewFile());
        return $this;
    }

 
    public function getUrl()
    {
        $baseDir = Mage::getBaseDir('media');
        $path = str_replace($baseDir . DS, "", $this->_newFile);
        return Mage::getBaseUrl('media') . str_replace(DS, '/', $path);
    }

    public function push()
    {
        $this->getImageProcessor()->display();
    }

    public function setDestinationSubdir($dir)
    {
        $this->_destinationSubdir = $dir;
        return $this;
    }

   
    public function getDestinationSubdir()
    {
        return $this->_destinationSubdir;
    }

    public function isCached()
    {
        return file_exists($this->_newFile);
    }

    
    public function setWatermarkFile($file)
    {
        $this->_watermarkFile = $file;
        return $this;
    }

  
    public function getWatermarkFile()
    {
        return $this->_watermarkFile;
    }

 
    protected function _getWatermarkFilePath()
    {
        $filePath = false;

        if (!$file = $this->getWatermarkFile())
        {
            return $filePath;
        }

        $baseDir = Mage::getSingleton('catalog/product_media_config')->getBaseMediaPath();

        if( file_exists($baseDir . '/watermark/stores/' . Mage::app()->getStore()->getId() . $file) ) {
            $filePath = $baseDir . '/watermark/stores/' . Mage::app()->getStore()->getId() . $file;
        } elseif ( file_exists($baseDir . '/watermark/websites/' . Mage::app()->getWebsite()->getId() . $file) ) {
            $filePath = $baseDir . '/watermark/websites/' . Mage::app()->getWebsite()->getId() . $file;
        } elseif ( file_exists($baseDir . '/watermark/default/' . $file) ) {
            $filePath = $baseDir . '/watermark/default/' . $file;
        } elseif ( file_exists($baseDir . '/watermark/' . $file) ) {
            $filePath = $baseDir . '/watermark/' . $file;
        } else {
            $baseDir = Mage::getDesign()->getSkinBaseDir();
            if( file_exists($baseDir . $file) ) {
                $filePath = $baseDir . $file;
            }
        }

        return $filePath;
    }

   
    public function setWatermarkPosition($position)
    {
        $this->_watermarkPosition = $position;
        return $this;
    }

    
    public function getWatermarkPosition()
    {
        return $this->_watermarkPosition;
    }

   
    public function setWatermarkImageOpacity($imageOpacity)
    {
        $this->_watermarkImageOpacity = $imageOpacity;
        return $this;
    }

 
    public function getWatermarkImageOpacity()
    {
        return $this->_watermarkImageOpacity;
    }

    public function setWatermarkSize($size)
    {
        if( is_array($size) ) {
            $this->setWatermarkWidth($size['width'])
                ->setWatermarkHeigth($size['heigth']);
        }
        return $this;
    }

    public function setWatermarkWidth($width)
    {
        $this->_watermarkWidth = $width;
        return $this;
    }

 
    public function getWatermarkWidth()
    {
        return $this->_watermarkWidth;
    }

  
    public function setWatermarkHeigth($heigth)
    {
        $this->_watermarkHeigth = $heigth;
        return $this;
    }

    public function getWatermarkHeigth()
    {
        return $this->_watermarkHeigth;
    }

    public function clearCache()
    {
        #$directory = Mage::getBaseDir('media') . DS.'catalog'.DS.'product'.DS.'cache'.DS;
        $directory = Mage::getSingleton('tagimage/config')->getBaseMediaPath() .DS.'cache'.DS;
        $io = new Varien_Io_File();
        $io->rmdir($directory, true);
    }
}