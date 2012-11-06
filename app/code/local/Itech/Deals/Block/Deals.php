<?php
class Itech_Deals_Block_Deals extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getDeals()     
     { 
        if (!$this->hasData('deals')) {
            $this->setData('deals', Mage::registry('deals'));
        }
        return $this->getData('deals');
        
    }
}