<?php
/**
 * Slink Saasu Taxcode
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
 * @category   Saasu
 * @package    Slink for Magento
 * @copyright  Copyright (c) 2012 Saaslink
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Bernard Tai bernard@saaslink.com
 */
	
class Slink_MageSaasu_Model_Saasu_Taxcodes extends Slink_MageSaasu_Model_Saasu_Abstract{
 
    /* Saasu Taxcode Fields */
    protected $taxCodeUid='';
    protected $code='';
    protected $name='';
    protected $rate='';
    protected $postingAccountUid='';
    protected $isSale='';
    protected $isPurchase='';    
    protected $isShared='';
    protected $isActive='';
    
    public function get(){
        if(!($response = $this->curl_send($this->url(), '443', '1', false, ''))){        
            return false;
        }
        
        $doc = new DOMDocument();
        $doc->loadXML($response);
		$x = $doc->documentElement;
		
        $taxcodes = $doc->getElementsByTagName('taxCodeListItem');
        if(count($taxcodes)>0) $data = array();
        
        foreach($taxcodes as $taxcode){
            $a = array();
            foreach($taxcode->childNodes as $field){
                if($field->nodeType==1) $a[$field->nodeName] = $field->nodeValue;
            }
            $data[] = $a;
        }
        
        return $data;    
    }
    
    public function url(){
        if($this->getData('fileuid') && $this->getData('wsaccesskey')){
            if(substr($this->endpoint, strlen($this->endpoint)-1, 1)=="/"){
                $this->endpoint = substr($this->endpoint, 0, strlen($this->endpoint)-1);                
            }
            return $this->endpoint.DS."TaxCodeList?wsaccesskey=".$this->getData('wsaccesskey')."&fileuid=".$this->getData('fileuid');
        }else throw new Exception('Saasu credentials not found. This function requires Saasu File UID and Web Services Access Key');        
        
    }     
}

