 <?php
class Atd_Quicklook_Block_Configurable extends Mage_Core_Block_Template
{
    protected $_prices      = array();
    protected $_resPrices   = array();

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


    


    public function getAllowAttributes()
    {
        return $this->getProduct()->getTypeInstance(true)
            ->getConfigurableAttributes($this->getProduct());
    }

    public function hasOptions()
    {
        $attributes = $this->getAllowAttributes();
        if (count($attributes)) {
            foreach ($attributes as $key => $attribute) {
                /** @var Mage_Catalog_Model_Product_Type_Configurable_Attribute $attribute */
                if ($attribute->getData('prices')) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getAllowProducts()
    {
        if (!$this->hasAllowProducts()) {
            $products = array();
            $allProducts = $this->getProduct()->getTypeInstance(true)
                ->getUsedProducts(null, $this->getProduct());
            foreach ($allProducts as $product) {
                if ($product->isSaleable()) {
                    $products[] = $product;
                }
            }
            $this->setAllowProducts($products);
        }
        return $this->getData('allow_products');
    }

    public function getJsonConfig()
    {
        $attributes = array();
        $options = array();
        $store = Mage::app()->getStore();
        foreach ($this->getAllowProducts() as $product) {
            $productId  = $product->getId();

            foreach ($this->getAllowAttributes() as $attribute) {
                $productAttribute = $attribute->getProductAttribute();
                $attributeValue = $product->getData($productAttribute->getAttributeCode());
                if (!isset($options[$productAttribute->getId()])) {
                    $options[$productAttribute->getId()] = array();
                }

                if (!isset($options[$productAttribute->getId()][$attributeValue])) {
                    $options[$productAttribute->getId()][$attributeValue] = array();
                }
                $options[$productAttribute->getId()][$attributeValue][] = $productId;
            }
        }

        $this->_resPrices = array(
            $this->_preparePrice($this->getProduct()->getFinalPrice())
        );

        foreach ($this->getAllowAttributes() as $attribute) {
            $productAttribute = $attribute->getProductAttribute();
            $attributeId = $productAttribute->getId();
            $info = array(
               'id'        => $productAttribute->getId(),
               'code'      => $productAttribute->getAttributeCode(),
               'label'     => $attribute->getLabel(),
               'options'   => array()
            );

            $optionPrices = array();
            $prices = $attribute->getPrices();
            if (is_array($prices)) {
                foreach ($prices as $value) {
                    if(!$this->_validateAttributeValue($attributeId, $value, $options)) {
                        continue;
                    }

                    $info['options'][] = array(
                        'id'    => $value['value_index'],
                        'label' => $value['label'],
                        'price' => $this->_preparePrice($value['pricing_value'], $value['is_percent']),
                        'products'   => isset($options[$attributeId][$value['value_index']]) ? $options[$attributeId][$value['value_index']] : array(),
                    );
                    $optionPrices[] = $this->_preparePrice($value['pricing_value'], $value['is_percent']);
                    //$this->_registerAdditionalJsPrice($value['pricing_value'], $value['is_percent']);
                }
            }
            /**
             * Prepare formated values for options choose
             */
            foreach ($optionPrices as $optionPrice) {
                foreach ($optionPrices as $additional) {
                    $this->_preparePrice(abs($additional-$optionPrice));
                }
            }
            if($this->_validateAttributeInfo($info)) {
               $attributes[$attributeId] = $info;
            }
        }
        /*echo '<pre>';
        print_r($this->_prices);
        echo '</pre>';die();*/

        $_request = Mage::getSingleton('tax/calculation')->getRateRequest(false, false, false);
        $_request->setProductClassId($this->getProduct()->getTaxClassId());
        $defaultTax = Mage::getSingleton('tax/calculation')->getRate($_request);

        $_request = Mage::getSingleton('tax/calculation')->getRateRequest();
        $_request->setProductClassId($this->getProduct()->getTaxClassId());
        $currentTax = Mage::getSingleton('tax/calculation')->getRate($_request);

        $taxConfig = array(
            'includeTax'        => Mage::helper('tax')->priceIncludesTax(),
            'showIncludeTax'    => Mage::helper('tax')->displayPriceIncludingTax(),
            'showBothPrices'    => Mage::helper('tax')->displayBothPrices(),
            'defaultTax'        => $defaultTax,
            'currentTax'        => $currentTax,
            'inclTaxTitle'      => Mage::helper('catalog')->__('Incl. Tax'),
        );

        $config = array(
            'attributes'        => $attributes,
            'template'          => str_replace('%s', '#{price}', $store->getCurrentCurrency()->getOutputFormat()),
//            'prices'          => $this->_prices,
            'basePrice'         => $this->_registerJsPrice($this->_convertPrice($this->getProduct()->getFinalPrice())),
            'oldPrice'          => $this->_registerJsPrice($this->_convertPrice($this->getProduct()->getPrice())),
            'productId'         => $this->getProduct()->getId(),
            'chooseText'        => Mage::helper('catalog')->__('Choose an Option...'),
            'taxConfig'         => $taxConfig,
        );

        return Mage::helper('core')->jsonEncode($config);
    }

    /**
     * Validating of super product option value
     *
     * @param array $attribute
     * @param array $value
     * @param array $options
     * @return boolean
     */
    protected function _validateAttributeValue($attributeId, &$value, &$options)
    {
        if(isset($options[$attributeId][$value['value_index']])) {
            return true;
        }

        return false;
    }

    /**
     * Validation of super product option
     *
     * @param array $info
     * @return boolean
     */
    protected function _validateAttributeInfo(&$info)
    {
        if(count($info['options']) > 0) {
            return true;
        }
        return false;
    }

    protected function _preparePrice($price, $isPercent=false)
    {
        if ($isPercent && !empty($price)) {
            $price = $this->getProduct()->getFinalPrice()*$price/100;
        }

        return $this->_registerJsPrice($this->_convertPrice($price, true));
    }

    protected function _registerJsPrice($price)
    {
        $jsPrice            = str_replace(',', '.', $price);

//        if (!isset($this->_prices[$jsPrice])) {
//            $this->_prices[$jsPrice] = strip_tags(Mage::app()->getStore()->formatPrice($price));
//        }
        return $jsPrice;
    }

    protected function _convertPrice($price, $round=false)
    {
        if (empty($price)) {
            return 0;
        }

        $price = Mage::app()->getStore()->convertPrice($price);
        if ($round) {
            $price = Mage::app()->getStore()->roundPrice($price);
        }


        return $price;
    }
    
}