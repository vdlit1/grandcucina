<?php
/**
 * Slink Saasu Bank Account
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
	
class Slink_MageSaasu_Model_Saasu_Bankaccounts extends Slink_MageSaasu_Model_Saasu_Abstract{
 
    /* Saasu Bank Account Fields */
    protected $bankAccountUid='';
    protected $utcFirstCreated='';
    protected $utcLastModified='';
    protected $lastUpdatedUid='';
    protected $type='';
    protected $name='';
    protected $ledgerCode='';
    protected $isActive='';
    protected $isInBuilt='';
    protected $displayName='';
    protected $bsb='';
    protected $accountNumber='';
    
    public function get(){
        if(!($response = $this->curl_send($this->url(), '443', '1', false, ''))){        
            return false;
        }
        
        $doc = new DOMDocument();
        $doc->loadXML($response);
		$x = $doc->documentElement;
		
        $accounts = $doc->getElementsByTagName('bankAccountListItem');
        if(count($accounts)>0) $data = array();
        
        foreach($accounts as $account){
            $a = array();
            foreach($account->childNodes as $field){
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
            return $this->endpoint.DS."BankAccountList?wsaccesskey=".$this->getData('wsaccesskey')."&fileuid=".$this->getData('fileuid');
        }else throw new Exception('Saasu credentials not found. This function requires Saasu File UID and Web Services Access Key');        
    }
}

