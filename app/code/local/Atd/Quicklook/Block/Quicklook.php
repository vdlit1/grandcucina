<?php
class Atd_Quicklook_Block_Quicklook extends Mage_Core_Block_Template
{


    public function getProducts(){
        $model = Mage::getModel('catalog/product');
        $product_id = $this->getRequest()->getParam('productid');
        $product = $model->load($product_id);
        return $product;
    }


    public function getProduct()
    {
        $product = $this->getProducts();
        if (is_null($product->getTypeInstance(true)->getStoreFilter($product))) {
            $product->getTypeInstance(true)->setStoreFilter(Mage::app()->getStore(), $product);
        }

        return $product;
    }


     protected $_priceBlock = array();
    protected $_priceBlockDefaultTemplate = 'catalog/product/price.phtml';
    protected $_tierPriceDefaultTemplate  = 'catalog/product/view/tierprices.phtml';
    protected $_priceBlockTypes = array();
   

    public function getAddToCartUrl($product, $additional = array()) {
        if ($this->getRequest()->getParam('wishlist_next')) {
            $additional['wishlist_next'] = 1;
        }

        return $this->helper('checkout/cart')->getAddUrl($product, $additional);
    }

    /**
     * Enter description here...
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */




    protected function _getPriceBlock($productTypeId)
    {
        if (!isset($this->_priceBlock[$productTypeId])) {
            $block = 'catalog/product_price';
            if (isset($this->_priceBlockTypes[$productTypeId])) {
                if ($this->_priceBlockTypes[$productTypeId]['block'] != '') {
                    $block = $this->_priceBlockTypes[$productTypeId]['block'];
                }
            }
            $this->_priceBlock[$productTypeId] = $this->getLayout()->createBlock($block);
        }
        return $this->_priceBlock[$productTypeId];
    }

    protected function _getPriceBlockTemplate($productTypeId)
    {
        if (isset($this->_priceBlockTypes[$productTypeId])) {
            if ($this->_priceBlockTypes[$productTypeId]['template'] != '') {
                return $this->_priceBlockTypes[$productTypeId]['template'];
            }
        }
        return $this->_priceBlockDefaultTemplate;
    }

    /**
     * Returns product price block html
     *
     * @param Mage_Catalog_Model_Product $product
     * @param boolean $displayMinimalPrice
     */
    public function getPriceHtml($product, $displayMinimalPrice = false, $idSuffix='')
    {
        return $this->_getPriceBlock($product->getTypeId())
            ->setTemplate($this->_getPriceBlockTemplate($product->getTypeId()))
            ->setProduct($product)
            ->setDisplayMinimalPrice($displayMinimalPrice)
            ->setIdSuffix($idSuffix)
            ->setUseLinkForAsLowAs($this->_useLinkForAsLowAs)
            ->toHtml();
    }
    
//    


    public function getJson() {
        $config = array();
        if (!$this->hasOptions()) {
            return Mage::helper('core')->jsonEncode($config);
        }

        $_request = Mage::getSingleton('tax/calculation')->getRateRequest(false, false, false);
        $_request->setProductClassId($this->getProduct()->getTaxClassId());
        $defaultTax = Mage::getSingleton('tax/calculation')->getRate($_request);

        $_request = Mage::getSingleton('tax/calculation')->getRateRequest();
        $_request->setProductClassId($this->getProduct()->getTaxClassId());
        $currentTax = Mage::getSingleton('tax/calculation')->getRate($_request);

        $_regularPrice = $this->getProduct()->getPrice();
        $_finalPrice = $this->getProduct()->getFinalPrice();
        $_priceInclTax = Mage::helper('tax')->getPrice($this->getProduct(), $_finalPrice, true);
        $_priceExclTax = Mage::helper('tax')->getPrice($this->getProduct(), $_finalPrice);

        $config = array(
                'productId'           => $this->getProduct()->getId(),
                'priceFormat'         => Mage::app()->getLocale()->getJsPriceFormat(),
                'includeTax'          => Mage::helper('tax')->priceIncludesTax() ? 'true' : 'false',
                'showIncludeTax'      => Mage::helper('tax')->displayPriceIncludingTax(),
                'showBothPrices'      => Mage::helper('tax')->displayBothPrices(),
                'productPrice'        => Mage::helper('core')->currency($_finalPrice, false, false),
                'productOldPrice'     => Mage::helper('core')->currency($_regularPrice, false, false),
                'skipCalculate'       => ($_priceExclTax != $_priceInclTax ? 0 : 1),
                'defaultTax'          => $defaultTax,
                'currentTax'          => $currentTax,
                'idSuffix'            => '_clone',
                'oldPlusDisposition'  => 0,
                'plusDisposition'     => 0,
                'oldMinusDisposition' => 0,
                'minusDisposition'    => 0,
        );

        $responseObject = new Varien_Object();
        Mage::dispatchEvent('catalog_product_view_config', array('response_object'=>$responseObject));
        if (is_array($responseObject->getAdditionalOptions())) {
            foreach ($responseObject->getAdditionalOptions() as $option=>$value) {
                $config[$option] = $value;
            }
        }

        return Mage::helper('core')->jsonEncode($config);
    }

      public function hasOptions() {
        if ($this->getProduct()->getTypeInstance(true)->hasOptions($this->getProduct())) {
            return true;
        }
        return false;
    }

    /**
     * Check if product has required options
     *
     * @return bool
     */
    public function hasRequiredOptions() {
        return $this->getProduct()->getTypeInstance(true)->hasRequiredOptions($this->getProduct());
    }
}