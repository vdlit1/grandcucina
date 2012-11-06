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
class  Atd_Tagimage_Helper_Image extends Mage_Core_Helper_Abstract
{
    protected $_model;
    protected $_scheduleResize = false;
    protected $_scheduleRotate = false;
    protected $_angle;

    protected $_watermark;
    protected $_watermarkPosition;
    protected $_watermarkSize;
    protected $_watermarkImageOpacity;

    protected $_banner;
    protected $_imageFile;
    protected $_placeholder;

    /**
     * Reset all previos data
     */
    protected function _reset()
    {
        $this->_model = null;
        $this->_scheduleResize = false;
        $this->_scheduleRotate = false;
        $this->_angle = null;
        $this->_watermark = null;
        $this->_watermarkPosition = null;
        $this->_watermarkSize = null;
        $this->_watermarkImageOpacity = null;
        $this->_banner = null;
        $this->_imageFile = null;
        return $this;
    }

    public function init($imageFile=null)
    {
        $this->_reset();
        $this->_setModel(Mage::getModel('tagimage/tagsimages_image'));
        $this->setWatermark(Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_image"));
        $this->setWatermarkImageOpacity(Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_imageOpacity"));
        $this->setWatermarkPosition(Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_position"));
        $this->setWatermarkSize(Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_size"));

        if ($imageFile) {
            $this->setImageFile($imageFile);
        }
        else {
           
        }
        return $this;
    }

  
    public function resize($width, $height = null)
    {
        $this->_getModel()->setWidth($width)->setHeight($height);
        $this->_scheduleResize = true;
        return $this;
    }

  
    public function setQuality($quality)
    {
        $this->_getModel()->setQuality($quality);
        return $this;
    }

    public function keepAspectRatio($flag)
    {
        $this->_getModel()->setKeepAspectRatio($flag);
        return $this;
    }

  
    public function keepFrame($flag, $position = array('center', 'middle'))
    {
        $this->_getModel()->setKeepFrame($flag);
        return $this;
    }

    public function keepTransparency($flag, $alphaOpacity = null)
    {
        $this->_getModel()->setKeepTransparency($flag);
        return $this;
    }

  
    public function constrainOnly($flag)
    {
        $this->_getModel()->setConstrainOnly($flag);
        return $this;
    }

    
    public function backgroundColor($colorRGB)
    {
       
        if (!is_array($colorRGB)) {
            $colorRGB = func_get_args();
        }
        $this->_getModel()->setBackgroundColor($colorRGB);
        return $this;
    }

    public function rotate($angle)
    {
        $this->setAngle($angle);
        $this->_getModel()->setAngle($angle);
        $this->_scheduleRotate = true;
        return $this;
    }

  
    public function watermark($fileName, $position, $size=null, $imageOpacity=null)
    {
        $this->setWatermark($fileName)
            ->setWatermarkPosition($position)
            ->setWatermarkSize($size)
            ->setWatermarkImageOpacity($imageOpacity);
        return $this;
    }

    public function placeholder($fileName)
    {
        $this->_placeholder = $fileName;
    }

    public function getPlaceholder()
    {
        if (!$this->_placeholder) {
            $attr = $this->_getModel()->getDestinationSubdir();
            $this->_placeholder = 'images/catalog/product/placeholder/'.$attr.'.jpg';
        }
        return $this->_placeholder;
    }

    public function __toString()
    {
        try {
            if( $this->getImageFile() ) {
                $this->_getModel()->setBaseFile( $this->getImageFile() );
            } else {
                $this->_getModel()->setBaseFile( $this->getBanner()->getData($this->_getModel()->getDestinationSubdir()) );
            }

            if( $this->_getModel()->isCached() ) {
                return $this->_getModel()->getUrl();
            } else {
                if( $this->_scheduleRotate ) {
                    $this->_getModel()->rotate( $this->getAngle() );
                }

                if ($this->_scheduleResize) {
                    $this->_getModel()->resize();
                }

                if( $this->getWatermark() ) {
                    $this->_getModel()->setWatermark($this->getWatermark());
                }

                $url = $this->_getModel()->saveFile()->getUrl();
            }
        } catch( Exception $e ) {
            $url = Mage::getDesign()->getSkinUrl($this->getPlaceholder());
        }
        return $url;
    }

    protected function _setModel($model)
    {
        $this->_model = $model;
        return $this;
    }

    protected function _getModel()
    {
        return $this->_model;
    }

    protected function setAngle($angle)
    {
        $this->_angle = $angle;
        return $this;
    }

    protected function getAngle()
    {
        return $this->_angle;
    }

    
    protected function setWatermark($watermark)
    {
        $this->_watermark = $watermark;
        $this->_getModel()->setWatermarkFile($watermark);
        return $this;
    }

  
    protected function getWatermark()
    {
        return $this->_watermark;
    }

    
    protected function setWatermarkPosition($position)
    {
        $this->_watermarkPosition = $position;
        $this->_getModel()->setWatermarkPosition($position);
        return $this;
    }

   
    protected function getWatermarkPosition()
    {
        return $this->_watermarkPosition;
    }

    public function setWatermarkSize($size)
    {
        $this->_watermarkSize = $size;
        $this->_getModel()->setWatermarkSize($this->parseSize($size));
        return $this;
    }

   
    protected function getWatermarkSize()
    {
        return $this->_watermarkSize;
    }

  
    public function setWatermarkImageOpacity($imageOpacity)
    {
        $this->_watermarkImageOpacity = $imageOpacity;
        $this->_getModel()->setWatermarkImageOpacity($imageOpacity);
        return $this;
    }

   
    protected function getWatermarkImageOpacity()
    {
        if( $this->_watermarkImageOpacity ) {
            return $this->_watermarkImageOpacity;
        }

        return $this->_getModel()->getWatermarkImageOpacity();
    }

    protected function setBanner($banner)
    {
        $this->_banner = $banner;
        return $this;
    }

    protected function getBanner()
    {
        return $this->_banner;
    }

    protected function setImageFile($file)
    {
        $this->_imageFile = $file;
        return $this;
    }

    protected function getImageFile()
    {
        return $this->_imageFile;
    }

    
    protected function parseSize($string)
    {
        $size = explode('x', strtolower($string));
        if( sizeof($size) == 2 ) {
            return array(
                'width' => ($size[0] > 0) ? $size[0] : null,
                'heigth' => ($size[1] > 0) ? $size[1] : null,
            );
        }
        return false;
    }

    
    public function getOriginalWidth()
    {
        return $this->_getModel()->getImageProcessor()->getOriginalWidth();
    }

    
    public function getOriginalHeigh()
    {
        return $this->getOriginalHeight();
    }

    
    public function getOriginalHeight()
    {
        return $this->_getModel()->getImageProcessor()->getOriginalHeight();
    }

   
    public function getOriginalSizeArray()
    {
        return array(
            $this->getOriginalWidth(),
            $this->getOriginalHeight()
        );
    }

   
    public function validateUploadFile($filePath) {
        if (!getimagesize($filePath)) {
            Mage::throwException($this->__('Disallowed file type.'));
        }
        return true;
    }

}
