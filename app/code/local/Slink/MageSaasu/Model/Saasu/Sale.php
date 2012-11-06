<?php
/**
 * Slink Saasu Sale
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
	
class Slink_MageSaasu_Model_Saasu_Sale extends Slink_MageSaasu_Model_Saasu_Abstract{
 
    /* Saasu Sale Fields */
    protected $uid='';
    protected $lastUpdatedUid='';
    protected $transactionType='';
    protected $date='';    
    protected $contactUid='';

	protected $summary='';    
    protected $tags = '';
	protected $notes='';       
    protected $ccy='';    
    protected $autoPopulateFxRate='';    
    protected $fcToBcFxRate='';    
	protected $requiresFollowUp='';    
	protected $shipToContactUid = '';
    protected $dueOrExpiryDate='';
    protected $layout='';    
	protected $status='';    
    protected $invoiceType='';
	protected $invoiceNumber = '';
    protected $purchaseOrderNumber='';
    protected $externalNotes='';
    
	protected $itemInvoiceItems=array();
	protected $serviceInvoiceItems=array();    

    protected $quickPayment=array();
    
    protected $isSent='';
    protected $tradingTerms=array();
    protected $totalAmountInclTax='';
    protected $totalAmountExclTax='';    
    protected $totalTaxAmount='';    

    protected $emailMessage=array();
    
    
	public function addItemInvoiceItem($quantity, $itemUid, $description, $taxCode, $unitPriceInclTax, $percentageDiscount){
		$items = $this->getData('itemInvoiceItems');
        $items[] = array('quantity'=>$quantity,
                         'inventoryItemUid'=>$itemUid,
                         'description'=>$description,
                         'taxCode'=>$taxCode,
                         'unitPriceInclTax'=>$unitPriceInclTax,
                         'percentageDiscount'=>$percentageDiscount);
        
        $this->setData('itemInvoiceItems', $items);
	}
	public function addServiceInvoiceItem($description, $accountUid, $taxCode, $totalAmountInclTax){
        $services = $this->getData('serviceInvoiceItems');
        $services[] = array('description'=>$description,
                            'accountUid'=>$accountUid,
                            'taxCode'=>$taxCode,
                            'totalAmountInclTax'=>$totalAmountInclTax);
        
        $this->setData('serviceInvoiceItems', $services);
	}    
	public function addQuickPayment($datePaid, $dateCleared, $bankedToAccountUid, $amount, $reference, $summary){
        $payments = $this->getData('quickPayment');
        $payments[] = array('datePaid'=>$datePaid,
                            'dateCleared'=>$dateCleared,
                            'bankedToAccountUid'=>$bankedToAccountUid,
                            'amount'=>$amount,
                            'reference'=>$reference,
                            'summary'=>$summary);
        $this->setData('quickPayment', $payments);
	}    
    
    public function get($uid){
        if($uid=="") $uid = $this->getData('uid');
        
        if($uid > 0){
            
            if(!($response = $this->curl_send($this->getUrl($uid), '443', '1', false, ''))){        
                return false;
            }
        
            $doc = new DOMDocument();
            $doc->loadXML($response);
            $x = $doc->documentElement;
            
            $sales = $doc->getElementsByTagName('invoice');
            
            foreach($sales as $sale){
                foreach($sale->childNodes as $field){
                    if($field->nodeType==1) $this->setData($field->nodeName, $field->nodeValue);
                }
                $this->setData('uid', $sale->getAttribute('uid'));
                $this->setData('lastUpdatedUid', $sale->getAttribute('lastUpdatedUid'));                
            }               
        }
    }
    public function getByInvoiceNumber($invoice_number=""){
        if($invoice_number=="") $invoice_number = $this->getData('invoiceNumber');
        
        if($invoice_number<>""){
            
            if(!($response = $this->curl_send($this->listUrl($invoice_number), '443', '1', false, ''))){        
                throw new Exception('No response');
                return false;
            }    

            $doc = new DOMDocument();
            $doc->loadXML($response);
            $x = $doc->documentElement;

            $sales = $doc->getElementsByTagName('invoiceListItem'); 
            
            foreach($sales as $sale){
                $a = array();
                foreach($sale->childNodes as $field){
                    if($field->nodeType==1) $a[$field->nodeName] = $field->nodeValue;
                }
                if($a['invoiceNumber']==$invoice_number){
                    /* Get Contact info by making a separate get call. Returned fields for 'contact' are different to 'contactList' */
                    $this->get($a['invoiceUid']);
                    
                    
                }  
            }
            return false;
        }
    }
    public function post($data){

        if(!($response = $this->curl_send($this->postUrl() , '443', '1', true, $this->xml()))){        
            throw new Exception('No response');
            return false;
        }
        
		$doc = new DOMDocument();
		$doc->loadXML($response); 
		$x = $doc->documentElement;

        $errors = $doc->getElementsByTagName('errors');
        
        $result=array();
        
        if($errors->length>0){
            foreach($errors->childNodes as $node){
                if($node->nodeType==1 && $node->nodeName=="error"){
                    $error = array();
                    foreach($error->childNodes as $info){
                        if($info->nodeName=="type") $error['code'] = $info->nodeValue;
                        elseif($info->nodeName=="message") $error['message'] = $info->nodeValue;
                    }
                    $result[] = $error;
                }
            }
            $this->setError($result);
            return false;
        }else{
            
            if($this->getData('uid') && $this->getData('lastUpdatedUid')){
                $action = $doc->getElementsByTagName('updateInvoiceResult')->item(0);
                $this->setData('uid', $action->getAttribute('updatedEntityUid'));
                $this->setData('lastUpdatedUid', $action->getAttribute('lastUpdatedUid'));
            }else{
                $action = $doc->getElementsByTagName('insertInvoiceResult')->item(0);
                $this->setData('uid', $action->getAttribute('insertedEntityUid'));
                $this->setData('lastUpdatedUid', $action->getAttribute('lastUpdatedUid'));
            }
            
            if($this->getData('uid')<>"" && $this->getData('lastUpdatedUid')<>"") return true;

        }
		return true;
    }
    
    public function getUrl($uid=''){
        if($uid=='') $uid = $this->getData('uid');
        if(!$uid) {
            throw new Exception('No Sale UID specified');            
            return false;
        }
        
        if(!$this->getData('fileuid') || !$this->getData('wsaccesskey')){
            throw new Exception('Saasu credentials not found. This function requires Saasu File UID and Web Services Access Key');                            
        }
        if(substr($this->endpoint, strlen($this->endpoint)-1, 1)=="/"){
            $this->setData('endpoint',  substr($this->endpoint, 0, strlen($this->endpoint)-1));                
        }
        return  $this->endpoint.DS.
                "invoice?wsaccesskey=".$this->getData('wsaccesskey')."&fileuid=".$this->getData('fileuid').
                "&uid=".$uid;
        
    }
    
    public function listUrl($invoice_number=""){
        if(!$this->getData('fileuid') || !$this->getData('wsaccesskey')){
            throw new Exception('Saasu credentials not found. This function requires Saasu File UID and Web Services Access Key');                            
        }
        
        if(substr($this->endpoint, strlen($this->endpoint)-1, 1)=="/"){
            $this->setData('endpoint', substr($this->endpoint, 0, strlen($this->endpoint)-1));                
        }
        
        return  $this->endpoint.DS.
                "invoiceList?wsaccesskey=".$this->getData('wsaccesskey')."&fileuid=".$this->getData('fileuid').
                "&transactionType=s".
                "&paidStatus=all".
                "&invoiceDateFrom=1970-01-01".
                "&invoiceDateTo=".Mage::getModel('core/date')->gmtDate('Y-m-d').
                ($invoice_number<>"" ? "&InvoiceNumberBeginsWith=".urlencode($invoice_number):"");
        
    }
    public function listByModifiedUrl($from="", $to=""){
        if(!$this->getData('fileuid') || !$this->getData('wsaccesskey')){
            throw new Exception('Saasu credentials not found. This function requires Saasu File UID and Web Services Access Key');
        }
        
        if(substr($this->endpoint, strlen($this->endpoint)-1, 1)=="/"){
            $this->setData('endpoint', substr($this->endpoint, 0, strlen($this->endpoint)-1));
        }

        return  $this->endpoint.DS.
        "invoiceList?wsaccesskey=".$this->getData('wsaccesskey')."&fileuid=".$this->getData('fileuid').
        "&transactionType=s".
        "&paidStatus=all".
        "&UtcLastModifiedFrom=".Mage::getModel('core/date')->gmtDate("Y-m-d\TH:i:s", Mage::getModel('core/date')->date("Y-m-d\TH:i:s", $from)).
                                ($to<>"" ? "&UtcLastModifiedTo=".Mage::getModel('core/date')->gmtDate("Y-m-d\TH:i:s", Mage::getModel('core/date')->date("Y-m-d\TH:i:s", $to)) :
                                "&UtcLastModifiedTo=".Mage::getModel('core/date')->gmtDate("Y-m-d\TH:i:s", Mage::getModel('core/date')->date("Y-m-d\TH:i:s"))).
        ($invoice_number<>"" ? "&InvoiceNumberBeginsWith=".urlencode($invoice_number):"");
        
    }
    
    public function postUrl(){
        if(!$this->getData('fileuid') || !$this->getData('wsaccesskey')){
            throw new Exception('Saasu credentials not found. This function requires Saasu File UID and Web Services Access Key');                            
        }
        if(substr($this->getData('endpoint'), strlen($this->getData('endpoint'))-1, 1)=="/"){
            $this->setData('endpoint', substr($this->getData('endpoint'), 0, strlen($this->getData('endpoint'))-1));                
        }                      
		return $this->getData('endpoint').DS.'tasks?wsaccesskey='.$this->getData('wsaccesskey').'&fileuid='.$this->getData('fileuid');                
    }
    
    public function xml(){
        
        if($this->getData('uid')<>"" && $this->getData('lastUpdatedUid')<>""){
            $isInsert=false;
        }else $isInsert=true;
        
        $xml = ($isInsert ? "<insertInvoice>\n" : "<updateInvoice>\n");
		$xml .= "<invoice ";
		$xml .= ($isInsert ? "uid=\"0\">\n" : "uid =\"".$this->xmlEntities($this->getData('uid'))."\" lastUpdatedUid= \"".$this->xmlEntities($this->getData('lastUpdatedUid'))."\">\n");
		$xml .= "<transactionType>".$this->xmlEntities($this->getData('transactionType'))."</transactionType>\n";
		$xml .= "<date>".$this->xmlEntities($this->getData('date'))."</date>\n";
		$xml .= "<contactUid>".$this->xmlEntities($this->getData('contactUid'))."</contactUid>\n";
		$xml .= "<summary>".$this->xmlEntities($this->getData('summary'))."</summary>\n";                
		$xml .= "<tags>".$this->xmlEntities($this->getData('tags'))."</tags>\n";		        
		$xml .= "<notes>".$this->xmlEntities($this->getData('notes'))."</notes>\n";
		$xml .= "<ccy>".$this->xmlEntities($this->getData("ccy"))."</ccy>\n";
		$xml .= "<autoPopulateFxRate>".$this->xmlEntities($this->getData("autoPopulateFxRate"))."</autoPopulateFxRate>\n";
		$xml .= "<fcToBcFxRate>".$this->xmlEntities($this->getData("fcToBcFxRate"))."</fcToBcFxRate>\n";        
		$xml .= "<requiresFollowUp>".$this->xmlEntities($this->getData('requiresFollowUp'))."</requiresFollowUp>\n";        
		$xml .= "<shipToContactUid>".$this->xmlEntities($this->getData('shipToContactUid'))."</shipToContactUid>\n";		
		$xml .= "<dueOrExpiryDate>".$this->xmlEntities(($this->getData('dueOrExpiryDate') ? date('Y-m-d', (int) $this->getData('dueOrExpiryDate')) : ''))."</dueOrExpiryDate>\n";		
		$xml .= "<layout>".$this->xmlEntities($this->getData('layout'))."</layout>\n";
		$xml .= "<status>".$this->xmlEntities($this->getData('status'))."</status>\n";
		$xml .= "<invoiceType>".$this->xmlEntities($this->getData('invoiceType'))."</invoiceType>\n";
		$xml .= "<invoiceNumber>".$this->xmlEntities($this->getData('invoiceNumber'))."</invoiceNumber>\n";
		$xml .= "<purchaseOrderNumber>".$this->xmlEntities($this->getData('purchaseOrderNumber'))."</purchaseOrderNumber>\n";
        
        if($terms = $this->getData('tradingTerms')){
            
        }
        
        $xml .= "<invoiceItems>\n";        
        if($this->getData('layout')=="S" && ($services = $this->getData('serviceInvoiceItems'))){
            foreach($services as $service){
                $xml .= "<serviceInvoiceItem>\n";                            
                $xml .= "<description>".$this->xmlEntities($service['description'])."</description>\n";
                $xml .= "<accountUid>".$this->xmlEntities($service['accountUid'])."</accountUid>\n";
                $xml .= "<taxCode>".$this->xmlEntities($service['taxCode'])."</taxCode>\n";
                $xml .= "<totalAmountInclTax>".$this->xmlEntities($service['totalAmountInclTax'])."</totalAmountInclTax>\n";                
                $xml .= "</serviceInvoiceItem>\n";                 
            }
        }elseif($items = $this->getData('itemInvoiceItems')){
            foreach($items as $item){
                $xml .= "<itemInvoiceItem>\n";            
                $xml .= "<quantity>".$this->xmlEntities($item['quantity'])."</quantity>\n";
                $xml .= "<inventoryItemUid>".$this->xmlEntities($item['inventoryItemUid'])."</inventoryItemUid>\n";
                $xml .= "<description>".$this->xmlEntities($item['description'])."</description>\n";
                $xml .= "<taxCode>".$this->xmlEntities($item['taxCode'])."</taxCode>\n";
                $xml .= "<unitPriceInclTax>".$this->xmlEntities($item['unitPriceInclTax'])."</unitPriceInclTax>\n";
                $xml .= "<percentageDiscount>".$this->xmlEntities($item['percentageDiscount'])."</percentageDiscount>\n";                                
                $xml .= "</itemInvoiceItem>\n";                               
            }
        }
        $xml .= "</invoiceItems>\n";                    
        if($payments = $this->getData('quickPayment')){
            foreach($payments as $payment){
                $xml .=  "<quickPayment>\n";
                $xml .= "<datePaid>".$this->xmlEntities($payment['datePaid'])."</datePaid>\n";                                
                $xml .= "<dateCleared>".$this->xmlEntities($payment['dateCleared'])."</dateCleared>\n";                                
                $xml .= "<bankedToAccountUid>".$this->xmlEntities($payment['bankedToAccountUid'])."</bankedToAccountUid>\n";                                
                $xml .= "<amount>".$this->xmlEntities($payment['amount'])."</amount>\n";                                
                $xml .= "<reference>".$this->xmlEntities($payment['reference'])."</reference>\n";                                                
                $xml .= "<summary>".$this->xmlEntities($payment['summary'])."</summary>\n";
                $xml .=  "</quickPayment>\n";
            }
        }
        
        if($email = $this->getData('emailMessage')){
            $xml .=  "<emailMessage>\n";
            $xml .= "<from>".$this->xmlEntities($email['from'])."</from>\n";                                
            $xml .= "<to>".$this->xmlEntities($email['to'])."</to>\n";                                
            $xml .= "<subject>".$this->xmlEntities($email['subject'])."</subject>\n";                                
            $xml .= "<body>".$this->xmlEntities($email['body'])."</body>\n";                                
            $xml .=  "</emailMesage>\n";
        }
        
		$xml .= "<externalNotes>".$this->xmlEntities($this->getData('externalNotes'))."</externalNotes>\n";

		$xml .= "<isSent>".$this->xmlEntities($this->getData('isSent'))."</isSent>\n";
        
        if(($items = $this->getData('invoiceItems')) && count($items)>0){
            if($this->layout=="S"){            
            }elseif($this->layout=="I"){
            }            
        }
        
        if(($quickPayment = $this->getData('quickPayment')) && count($quickPayment)>0){
            
        }
        $xml .= "</invoice>\n";
        $xml .= ($isInsert ? "</insertInvoice>\n" : "</updateInvoice>\n");
        return $xml;
        
    }
    public function setSale($sale){
		$this->addData(array(    'transactionType'=>'S',
                                 'date'=> Mage::getModel('core/date')->date('Y-m-d', $sale->getData('created_at')),
                                 'contactUid'=>'',
                                 'summary'=>'',
                                 'tags'=>'',
                                 'notes'=>'',                             
                                 'ccy'=>'',
                                 'autoPopulateFxRate'=>'',
                                 'fcToBcFxRate'=>'',
                                 'requiresFollowUp'=>'',
                                 'shipToContactUid'=>'',
                                 'dueOrExpiryDate'=>'',
                                 'layout'=>'',
                                 'status'=>'',
                                 'invoiceType'=>'',
                                 'invoiceNumber'=>'', //can also set to <Auto Number>
                                 'purchaseOrderNumber'=>'',
                                 'externalNotes'=>$sale->getData('customer_note'),
                                 'isSent'=>'false'
                                 ));
    }
}

