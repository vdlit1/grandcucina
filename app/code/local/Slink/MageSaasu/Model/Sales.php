<?php
/**
 * Slink for Magento
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
 * @category   Slink_MageSaasu
 * @package    Sales
 * @copyright  Copyright (c) 2009 Bernard Tai
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Bernard Tai bernard.tai@saaslink.net
 */

class Slink_MageSaasu_Model_Sales extends Mage_Core_Model_Abstract
{
	protected $sale; /* variable for use with internal functions */
	protected $slink_saasu_sale;	/* variable for use with internal functions */
    
	protected $saleAmount;

	protected $isGuestBilling = false;
	protected $isGuestShipping = false;
	protected $linkMessage = '';
    
    protected function _construct(){
		parent::_construct();
        $this->_init('slink/sales');		

    }
	public function link(){
		$this->linkMessage = ''; 

        $this->sale = Mage::getModel('sales/order')->load($this->getData('vid'));
        $sale = &$this->sale;
        
        if($sale->getId()>0){

			$config = Mage::getStoreConfig('slinksettings') ;						
			$this->slink_saasu_sale = Mage::getModel('slink/saasu_sale');
            $slink_saasu_sale = &$this->slink_saasu_sale;
        
            /* Look for item if UID available */
            if($this->getData('entity_uid')<>"" && $this->getData('updated_uid')<>"") $slink_saasu_sale->get($this->getData('entity_uid'));

            /* Look for Magento Order increment id (if not Auto Numbered) */
            if(!$config['sales']['saleAutoNumber']){
                if($slink_saasu_sale->getData('uid')=="" && $slink_saasu_sale->getData('lastUpdatedUid')=="" && $this->sale->getData('increment_id')>0) 
                    $slink_saasu_sale->getByInvoiceNumber($sale->getData('increment_id'));

            }
            
            if(($slink_saasu_sale->getData('uid')=="" && $slink_saasu_sale->getData('lastUpdatedUid')=="") 
               || /* If item has been updated - post anyway */
               $this->getData('transferred') < $this->sale->getData('updated_at')){
                
                $slink_saasu_sale->setSale( $this->sale );	                                
                $slink_saasu_sale->setData('status', $config['sales']['saasuSaleStatus']);
                $slink_saasu_sale->setData('layout', $config['sales']['saleLayout']);
                /* if Layout=S or I determine how to add items here */
                
                
                if($config['sales']['saleAutoNumber']){
                    $slink_saasu_sale->setData('invoiceNumber', '<Auto Number>');
                }else{
                    $slink_saasu_sale->setData('invoiceNumber', $config['sales']['salePrefix'].$this->sale->getData('increment_id'));
                }
                $slink_saasu_sale->setData('tags', $config['sales']['saleTags']);
                
                
                $slink_saasu_sale->setData('ccy', $this->sale->getData('order_currency_code'));
                /* Set quick payment bank account here */
                
                $this->_setContacts();
                $this->_setItems();
                $this->_setPayments();

                if($slink_saasu_sale->post()){
                    $slink_saasu_sale->setData('transferred' , Mage::getModel('core/date')->gmtTimestamp());
                }		                
            }
			
            if(($entity_uid = $slink_saasu_sale->getData('uid')) && ($updated_uid = $slink_saasu_sale->getData('lastUpdatedUid'))){
                                
				$this->addData(array('entity_uid'=>$entity_uid,
									 'updated_uid'=>$updated_uid,
									 'invoice_number'=>$slink_saasu_sale->getData('invoiceNumber'),
									 'transferred'=>$slink_saasu_sale->getData('transferred')
									 ));
                
                if(!$this->getData('transferred'))
                   $this->setData('transferred', Mage::getModel('core/date')->gmtTimestamp());
                
				$this->save();
                            
			}else{
				/** delete guest contacts created in Saasu for this sale **/
				if($config['contacts']['linkGuestContacts']){
					$slink_contact = Mage::getModel('slink/saasu_contact');
					if($this->isGuestBilling){
						$slink_contact->delete($slink_saasu_sale->getData('contactUid'));
					}
					if($this->isGuestShipping){
						$slink_contact->delete($slink_saasu_sale->getData('shipToContactUid'));
					}
				}
			}
			
			if($errors = $slink_saasu_sale->getError()){
				foreach($errors as $error){
					$this->linkMessage .= 'Sale '.$slink_saasu_sale->getData('increment_id').' - '.$error['code']. ' - '.$error['message'].($error['action'] ? ' ('.$error['action'].' )' : '').'<br>';
				}
			}					
			if($this->linkMessage) throw new Exception($this->linkMessage);			
			return true;
			
		}else return false;
				
	}
	
	public function unlink(){
		if($this->getData('id') > 0){	
			
			$this->addData(array('entity_uid'=>'',
								 'updated_uid'=>'',
								 'invoice_number'=>'',
								 'transferred'=>''));
			$this->save();
			return;
		}else throw new Exception ('No Sale with ID '.$this->getId());
			
	}
	private function _setContacts(){
		$config = Mage::getStoreConfig('slinksettings') ;
		if($this->sale && $this->slink_saasu_sale){
            
            $sale = &$this->sale;
            $slink_saasu_sale = &$this->slink_saasu_sale;
            
			$billing = $sale->getBillingAddress();
			$shipping = $sale->getShippingAddress();
            
            $billing_customer_address = Mage::getModel('customer/address')->load($billing->getData('customer_address_id'));
            $shipping_customer_address = Mage::getModel('customer/address')->load($shipping->getData('customer_address_id'));
            
			/** If sale contact is not a Guest **/
			if($sale->getData('customer_id')>0 && $billing_customer_address->getId()>0){

                if(($slink_billing =Mage::getModel('slink/contacts')->load($billing_customer_address->getId(), 'vid')) && !$slink_billing->getId()){
                    $slink_billing->setData('vid', $billing_customer_address->getId())->save();
                }
				if($slink_billing->getId()>0){
					$slink_billing->link();
                    if($slink_billing->getData('entity_uid')>0) $slink_saasu_sale->setData('contactUid', $slink_billing->getData('entity_uid'));
				}
				
                if(($slink_shipping = Mage::getModel('slink/contacts')->load($shipping_customer_address->getId(), 'vid')) && !$slink_shipping->getId()){
                    $slink_shipping->setData('vid', $shipping_customer_address->getId())->save();
                }
				if($slink_shipping->getId()>0){
                    $slink_shipping->link();
                    if($slink_shipping->getData('entity_uid')>0) $slink_saasu_sale->setData('shipToContactUid', $slink_shipping->getData('entity_uid'));
				}
			}elseif($config['contacts']['linkGuestContacts']){
                
				$this->isGuestBilling=false; $this->isGuestShipping=false;
                
				$slink_billing = Mage::getModel('slink/saasu_contact');
                $slink_billing->setContact($billing);
				$slink_billing->setData('contactId', 'GUESTBILLING'.$billing->getData('entity_id'));
                $slink_billing->setData('tags', $config['contactTags']);
				
				$slink_billing->getByContactId('GUESTBILLING'.$billing->getData('entity_id'));
				if($slink_billing->getData('uid') == "" && $slink_billing->getData('updated_uid') == ""){
					$slink_billing->post();
				}
				
				if($slink_billing->getData('uid') <>""){
					$this->isGuestBilling = true;
					$slink_saasu_sale->setData('contactUid', $slink_billing->getData('uid'));
				}else throw new Exception('Aborted. Could not create guest billing contact ('.$billing->getName().')');
				
				$slink_shipping = Mage::getModel('slink/saasu_contact');
                $slink_shipping->setContact($shipping);
				$slink_shipping->setData('contactId', 'GUESTSHIPPING'.$shipping->getData('entity_id'));
                $slink_shipping->setData('tags', $config['contactTags']);
				
				$slink_shipping->getByContactId('GUESTSHIPPING'.$shipping->getData('entity_id'));
				
				if($slink_shipping->getData('uid')=="" && $slink_shipping->getData('updated_uid')==""){
					$slink_shipping->post();
				}
				if($slink_shipping->getData('uid')<>""){
                    $this->isGuestShipping = true;
					$slink_saasu_sale->setData('shipToContactUid', $slink_shipping->getData('uid'));
				}else throw new Exception('Aborted. Could not create guest shipping contact ('.$shipping->getName().')');
                
			}elseif($config['contacts']['guestContactUid']<>""){
                $slink_saasu_sale->setData('contactUid', $config['contacts']['guestContactUid']);
				$slink_saasu_sale->setData('notes', "SHIPPING ADDRESS:\r\n".
										   $shipping->getData('lastname').', '.$shipping->getData('firstname')."\r\n".
										   $shipping->getData('street')."\r\n".
										   $shipping->getData('city')."\r\n".
										   $shipping->getData('region')." ".
										   $shipping->getData('postcode')." ".
										   $shipping->getData('country_id')."\r\n".
										   $shipping->getData('telephone')."\r\n".
										   $shipping->getData('email')
										   );
			}
		}
	}
	
	private function _setItems(){
        $sale = &$this->sale;
        $slink_saasu_sale = &$this->slink_saasu_sale;

		if($sale->getId()>0 && $slink_saasu_sale){
			$config = Mage::getStoreConfig('slinksettings') ;
			$items = $sale->getItemsCollection();
            
			foreach($items as $item){
				/**	Price info in Configurable product,
				 Qty info in Configurable and its Simple product */
				if($item->product_type == 'configurable'){
					continue;
				}
				/** Skip Bundle products. Price and Qty info in its Simple product */
				elseif($item->product_type == 'bundle'){
					continue;
				}else{
					if($item->parent_item_id > 0){ // Has a parent configurable product, use parent's price and qty info
						$parent = Mage::getModel('sales/order_item')->load($item->parent_item_id);
						if($parent->getId()>0 && $parent->product_type=='configurable') $i = $parent;
						else $i = $item;
					}else $i = $item;
					
					/* Grand Cucina */
					$product = Mage::getModel('catalog/product')->load(Mage::getModel('catalog/product')->getIdBySku($item->getData('sku')));
					if( $product->getData('grandcucina_buy_unit_qty')>1){
						$qty = $item->getData('qty_ordered') * $product->getData('grandcucina_buy_unit_qty');
						
					}else{
						$qty = $item->getData('qty_ordered');
					}
					/* End */
					
					$unitPrice = $i->getData('price');
					$unitTax = $i->getData('tax_amount') / $qty;
					$unitPriceInclTax = $unitPrice + $unitTax;
					$unitPriceInclTax = ( $i->getData('row_total') + $i->getData('tax_amount') - $i->getData('discount_amount')) / $qty;
                    
					if($product_id = Mage::getModel('catalog/product')->getIdBySku($item->getData('sku'))){
                        if(($slink_item = Mage::getModel('slink/items')->load($product_id, 'vid')) && !$slink_item->getId()){
                            $slink_item->setData(array('vid'=>$product_id));
                            $slink_item->save();
						}
                        $slink_item->link(); /* Link to ensure correct uid */
						$entity_uid = $slink_item->getData('entity_uid');
					}else{
						/* Item not found in catalog. Find item in Slink and link by SKU, else create */
						$slink_item = Mage::getModel('slink/saasu_item');
                        $slink_item->getBySku($i->getSku());
                        
						if(!$slink_item->getData('uid')) throw new Exception('Sale '.$this->sale->getData('increment_id').' - '.
                                                                             'Item '.$item->getSku().' not found.');
                        
                        $entity_uid = $slink_item->getData('uid');
					}
                    
					$slink_saasu_sale->addItemInvoiceItem($qty,
                                                    $entity_uid,
                                                    $item->getData('name'),
                                                    ($i->getData('tax_amount') > 0 ? $config['tax']['saleTaxCode'] : $config['tax']['saleTaxFreeCode']), //tax code
                                                    //											 ($discount > 0 ? ($unitPriceInclTax / (1 - $discount)) : $unitPriceInclTax),
                                                    $unitPriceInclTax,
                                                    //											 $item->getData('discount_percent'));
                                                    0);
					
                    $this->saleAmount += $qty * $unitPriceInclTax;
				}
			}
			
			if(($sale->getData('shipping_amount') + $sale->getData('shipping_tax_amount'))>0){
				if($config['items']['shippingItemCode']=="") throw new Exception('No item code provided for Shipping. (See Slink Settings > Items).');
            
                
                $shipping_item = Mage::getModel('slink/saasu_item');
                $shipping_item->getBySku($config['items']['shippingItemCode']);
                if(!$shipping_item->getData('uid')) throw new Exception ('No shipping item found for code '.$config['items']['shippingItemCode']);
                   
				$slink_saasu_sale->addItemInvoiceItem(1,
                                                      $shipping_item->getData('uid'),
                                                      $shipping_item->getData('description'),
                                                      ($sale->getData('shipping_tax_amount') > 0 ? $config['tax']['saleTaxCode'] : $config['tax']['saleTaxFreeCode']),
                                                      ($sale->getData('shipping_amount')+$sale->getData('shipping_tax_amount')),
                                                      0);    
				$this->saleAmount += $sale->getData('shipping_amount')+$sale->getData('shipping_tax_amount');
			}
			
			if(($coupon = $sale->getData('coupon_code')) <> ""){
				if($config['items']['couponItemCode']=="") throw new Exception( 'No item code provided for Coupon Discounts. (See Slink Settings > Items). ');
				
                $coupon_item = Mage::getModel('slink/saasu_item');
                $coupon_item->getBySku($config['items']['couponItemCode']);
                
                if(!$coupon_item->getData('uid')) throw new Exception ('No coupon item found for code '.$config['items']['couponItemCode']);
                   
				/** Adding coupon code to sale tags in Saasu **/
                $current_tags = $slink_saasu_sale->getData('tags');
                array_push($current_tags, $sale->getData('coupon_code'));
                $slink_saasu_sale->setData(array('tags'=>$current_tags));
                
                $slink_saasu_sale->addItemInvoiceItem(1,
											 $coupon_item->getData('uid'),
											 'Coupon Code "'.$sale->getData('coupon_code').'"',
											 '', //No tax code
											 0, // 0 value for line item
											 0); // 0 value for line item discount
            }
		}
	}
	
	private function _setPayments(){
        
        $sale = &$this->sale;
        $slink_saasu_sale = &$this->slink_saasu_sale;
        
		if($sale->getId()>0){
            
			$config = Mage::getStoreConfig('slinksettings') ;
			if($config['sales']['createQuickPayment']) {
				$bankAccountUid = trim($config['sales']['bankAccountUid']);
				if($curr = $this->sale->order_currency_code){
					$regexps = @unserialize($config['sales']['bankAccountCurrencyRegExp']);
                
					foreach($regexps as $exception){
						$exception_currency = $exception['regexp'];
						if(false===strpos($exception_currency, '/', 0)) {
							$exception_currency = '/'.$exception_currency.'/';
						}
						if(@preg_match($exception_currency, $sale->order_currency_code)){
							$bankAccountUid = $exception['value'];
						}
					}
				}
				if($bankAccountUid){
                    $slink_saasu_sale->addQuickPayment( Mage::getModel('core/date')->date('Y-m-d', $sale->getData('created_at')),
                                                        Mage::getModel('core/date')->date('Y-m-d', $sale->getData('created_at')),
                                                        $bankAccountUid,
                                                        $this->saleAmount,
                                                        '',
                                                        'Online Order '.$this->sale->getData('increment_id'));
				}else{
					$this->linkMessage .= 'No Bank Account assigned to Sale Payment. See Slink Settings > Sales > Create Payment.';
				}
			}
        }
				
	}
    
	
}