<?php
/**
 * Slink Saasu Item
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
	
class Slink_MageSaasu_Model_Saasu_Item extends Slink_MageSaasu_Model_Saasu_Abstract{
 
    /* Saasu Item Fields */
    protected $uid='';
    protected $lastUpdatedUid='';
    protected $utcFirstCreated='';
    protected $utcLastModified='';
    protected $code='';
    protected $description='';
    protected $isActive='';
    protected $isInventoried='';
    protected $assetAccountUid='';
    protected $stockOnHand='';
    protected $currentValue='';
    protected $quantityOnOrder='';
    protected $quantityCommitted='';
    protected $isBought='';
    protected $purchaseExpenseAccountUid='';
    protected $minimumStockLevel='';
    protected $primarySupplierContactUid='';
    protected $defaultReOrderQuantity='';
    protected $isSold='';
    protected $saleIncomeAccountUid='';
    protected $saleCoSAccountUid='';
    protected $sellingPrice='';
    protected $isSellingPriceIncTax='';
    protected $buyingPrice='';
    protected $isBuyingPriceIncTax='';
    protected $isVoucher='';
    protected $validFrom='';
    protected $validTo='';
    protected $isVirtual='';
    protected $isVisible='';
    
    /** Internal */
    protected $_enableCreateUpdate = false;
    protected $_defaultAssetAccountUid;
    protected $_defaultExpenseAccountUid;
    protected $_defaultIncomeAccountUid;
	protected $_defaultSaleTaxCode;
	protected $_defaultPurchaseTaxCode;
    
    public function __construct(){
        
        parent::__construct();
        
        $config = Mage::getStoreConfig('slinksettings');
        
        /** Assign default notes */
        if($notes = $config['items']['itemNotes']) $this->setData('notes', $notes);
        $this->addData(array('_enableCreateUpdate' => ($config['items']['enableCreateUpdate'] ? 1 : 0),
                             '_defaultAssetAccountUid' => $config['items']['defaultAssetAccountUid'],
                             '_defaultExpenseAccountUid' => $config['items']['defaultExpenseAccountUid'],
                             '_defaultIncomeAccountUid' => $config['items']['defaultIncomeAccountUid'],
                             '_defaultCoSAccountUid' => $config['items']['defaultCoSAccountUid'],
							 '_defaultPurchaseTaxCode'=>$config['tax']['purchaseTaxCode'],
							 '_defaultSaleTaxCode'=>$config['tax']['saleTaxCode']));

    }
    
    public function get($uid=""){
        if($uid=="") $uid = $this->getData('uid');
    
        if(!($response = $this->curl_send($this->getUrl($uid), '443', '1', false, ''))){        
            return false;
        }
    
        $doc = new DOMDocument();
        $doc->loadXML($response);
		$x = $doc->documentElement;
            
        $items = $doc->getElementsByTagName('inventoryItem');

        if(count($items)>0){
         
        }else{
            if(!($response = $this->curl_send($this->getUrl($uid, "combo"), '443', '1', false, ''))){        
                return false;
            }
            
            $doc = new DOMDocument();
            $doc->loadXML($response);
            $x = $doc->documentElement;
            $items = $doc->getElementsByTagName('comboItem');
            if(count($items)<1) return false;
        }
        
        foreach($items as $item){

            foreach($item->childNodes as $field){
                if($field->nodeType==1) $this->setData($field->nodeName, $field->nodeValue);
            }
            $this->setData('uid', $item->getAttribute('uid'));
            $this->setData('lastUpdatedUid', $item->getAttribute('lastUpdatedUid'));                
        }           
        
    }
    
    public function getBySku($code=""){        
        if($code=="") $code = $this->getData('code');        
        if(!($response = $this->curl_send($this->listUrl($code), '443', '1', false, ''))){        
            throw new Exception('No response');
            return false;
        }        
        
        $doc = new DOMDocument();
        $doc->loadXML($response);
		$x = $doc->documentElement;
        
        $items = $doc->getElementsByTagName('inventoryItem'); 
        
        if(count($items)>0){
            $data=array();         
        }else{
            if(!($response = $this->curl_send($this->listUrl($code, "combo"), '443', '1', false, ''))){        
                throw new Exception('No response');                
                return false;
            }

            $doc = new DOMDocument();
            $doc->loadXML($response);
            $x = $doc->documentElement;
            $items = $doc->getElementsByTagName('comboItem');
            if(count($items)<1) return false;
        }
        
        foreach($items as $item){
            $a = array();
            foreach($item->childNodes as $field){
                if($field->nodeType==1) $a[$field->nodeName] = $field->nodeValue;
            }
            if(trim($a['code']) == trim($code)){
                foreach($a as $field=>$value){
                    $this->setData($field, $value);
                }
                $this->setData('uid', $item->getAttribute('uid'));
                $this->setData('lastUpdatedUid', $item->getAttribute('lastUpdatedUid'));                
            }
        }           
        return false;
    }    
    
    public function getListByModified($from="", $to=""){
        
        $this->code="";
        
		if($to < $from) throw new Exception('Invalid time arguments. FROM greater than TO');
		if($to == 0) $to = time();        
        
        /** Get modified Inventory Items */        
        $url = $this->listUrl()."&UtcLastModifiedFrom=".gmdate('Y-m-d\TH:i:s', $from).
                                "&UtcLastModifiedTo=".gmdate('Y-m-d\TH:i:s', $to);
        
        if(!($response = $this->curl_send($url , '443', '1', false, ''))){        
            throw new Exception('No response');
            return false;
        }
        
        $doc = new DOMDocument();
        $doc->loadXML($response);
		$x = $doc->documentElement;
        
        $items = $doc->getElementsByTagName('inventoryItem'); 
        
        if(count($items)>0){
            $data=array();         
        }
        
        foreach($items as $item){
            $a = array();
            foreach($item->childNodes as $field){
                if($field->nodeType==1) $a[$field->nodeName] = $field->nodeValue;
            }
            $data[] = $a;
        } 
        
        /** Get modified Combo Items */
        $url = $this->listUrl("combo")."&UtcLastModifiedFrom=".gmdate('Y-m-d\TH:i:s', $from).
                                "&UtcLastModifiedTo=".gmdate('Y-m-d\TH:i:s', $to);
        
        if(!($response = $this->curl_send($url, '443', '1', false, ''))){        
            throw new Exception('No response');                
            return false;
        }
        
        $doc = new DOMDocument();
        $doc->loadXML($response);
        $x = $doc->documentElement;
        $items = $doc->getElementsByTagName('comboItem');

        if(count($items)>0 && !$data){
            $data=array();         
        }
        
        foreach($items as $item){
            $a = array();
            foreach($item->childNodes as $field){
                if($field->nodeType==1) $a[$field->nodeName] = $field->nodeValue;
            }
            $a['uid'] = $item->getAttribute('uid');
            $a['lastUpdatedUid'] = $item->getAttribute('lastUpdatedUid');
            
            $data[] = $a;
        }
        return $data;
        
    }
    
    public function post(){
        
        if(!($response = $this->curl_send($this->postUrl() , '443', '1', true, $this->xml()))){        
            throw new Exception('No response');
            return false;
        }
        
		$doc = new DOMDocument();
		$doc->loadXML($response);
		$x = $doc->documentElement;
        
        $errors = $doc->getElementsByTagName('errors');
        
        $result=array();
        
        if($errors->length){
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
                $action = $doc->getElementsByTagName('updateInventoryItemResult')->item(0);
                $this->setData('uid', $action->getAttribute('updatedEntityUid'));
                $this->setData('lastUpdatedUid', $action->getAttribute('lastUpdatedUid')); 
            }else{
                $action = $doc->getElementsByTagName('insertInventoryItemResult')->item(0);
                $this->setData('uid', $action->getAttribute('insertedEntityUid'));
                $this->setData('lastUpdatedUid', $action->getAttribute('lastUpdatedUid'));                                                                  
            }
            
            if($this->getData('uid')<>"" && $this->getData('lastUpdatedUid')<>"") return true;                               
        }

    }
    
    
    public function getUrl($uid="", $type=""){
        
        if($uid=="") $uid = $this->getData('uid');
        if(!$uid) return false;
        
        if(!$this->getData('fileuid') || !$this->getData('wsaccesskey')){
            throw new Exception('Saasu credentials not found. This function requires Saasu File UID and Web Services Access Key');                            
        }
        if(substr($this->endpoint, strlen($this->endpoint)-1, 1)=="/"){
            $this->setData('endpoint',  substr($this->endpoint, 0, strlen($this->endpoint)-1));                
        }
        return $this->endpoint.DS.($type<>"combo"? "InventoryItem" : "ComboItem").
        "?wsaccesskey=".$this->getData('wsaccesskey')."&fileuid=".$this->getData('fileuid')."&uid=".$uid;
        
    }
    
    public function listUrl($code="", $type=""){        
        
        if(!$this->getData('fileuid') || !$this->getData('wsaccesskey')){
            throw new Exception('Saasu credentials not found. This function requires Saasu File UID and Web Services Access Key');                            
        }
        
        if(substr($this->endpoint, strlen($this->endpoint)-1, 1)=="/"){
            $this->setData('endpoint', substr($this->endpoint, 0, strlen($this->endpoint)-1));                
        }
        
        return $this->endpoint.DS.
        ($type<>"combo"? "FullInventoryItemList" : "FullComboItemList").
        "?wsaccesskey=".$this->getData('wsaccesskey')."&fileuid=".$this->getData('fileuid').
        ($code<>"" ? "&codeBeginsWith=".urlencode($code) : "");
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
    
        
		$xml = ($isInsert ? "<insertInventoryItem>\n":"<updateInventoryItem>\n");
		$xml .="<inventoryItem ";
		$xml .= ($isInsert ? "uid=\"0\">\n" : "uid=\"".
                $this->xmlEntities($this->getData('uid'))."\" lastUpdatedUid=\"".$this->xmlEntities($this->getData('lastUpdatedUid'))."\">\n");
		$xml .= "<code>".$this->xmlEntities($this->getData('code'))."</code>\n";
		$xml .= "<description>".$this->xmlEntities($this->getData('description'))."</description>\n";
		$xml .= "<isActive>".$this->xmlEntities($this->getData('isActive'))."</isActive>\n";
		$xml .= "<isInventoried>".$this->xmlEntities($this->getData('isInventoried'))."</isInventoried>\n";
		$xml .= "<assetAccountUid>". $this->xmlEntities($this->getData('assetAccountUid'))."</assetAccountUid>\n";
		$xml .= "<stockOnHand>".$this->xmlEntities($this->getData('stockOnHand'))."</stockOnHand>\n";
		$xml .= "<currentValue>".$this->xmlEntities($this->getData('currentValue'))."</currentValue>\n";			
		$xml .= "<quantityOnOrder>".$this->xmlEntities($this->getData('quantityOnOrder'))."</quantityOnOrder>\n";			
		$xml .= "<quantityCommitted>".$this->xmlEntities($this->getData('quantityCommitted'))."</quantityCommitted>\n";			
		$xml .= "<isBought>".$this->xmlEntities($this->getData('isBought'))."</isBought>\n";
		$xml .= "<purchaseExpenseAccountUid>".$this->xmlEntities($this->getData('purchaseExpenseAccountUid'))."</purchaseExpenseAccountUid>\n";
        $xml .= "<purchaseTaxCode>".$this->xmlEntities($this->getData('purchaseTaxCode'))."</purchaseTaxCode>\n";		
		$xml .= "<minimumStockLevel>".$this->xmlEntities($this->getData('minimumStockLevel'))."</minimumStockLevel>\n";
		$xml .= "<primarySupplierContactUid>".$this->xmlEntities($this->getData('primarySupplierContactUid'))."</primarySupplierContactUid>\n";
		$xml .= "<defaultReOrderQuantity>".$this->xmlEntities($this->getData('defaultReOrderQuantity'))."</defaultReOrderQuantity>\n";
		$xml .= "<isSold>".$this->xmlEntities($this->getData('isSold'))."</isSold>\n";
		$xml .= "<saleIncomeAccountUid>".$this->xmlEntities($this->getData('saleIncomeAccountUid'))."</saleIncomeAccountUid>\n";
        $xml .= "<saleTaxCode>".$this->xmlEntities($this->getData('saleTaxCode'))."</saleTaxCode>\n";		
		$xml .= "<saleCoSAccountUid>".$this->xmlEntities($this->getData('saleCoSAccountUid'))."</saleCoSAccountUid>\n";
		$xml .= "<sellingPrice>".$this->xmlEntities($this->getData('sellingPrice'))."</sellingPrice>\n";
		$xml .= "<isSellingPriceIncTax>".$this->xmlEntities($this->getData('isSellingPriceIncTax'))."</isSellingPriceIncTax>\n";
		$xml .= "<buyingPrice>".$this->xmlEntities($this->getData('buyingPrice'))."</buyingPrice>\n";        
		$xml .= "<isBuyingPriceIncTax>".$this->xmlEntities($this->getData('isBuyingPriceIncTax'))."</isBuyingPriceIncTax>\n";        
		$xml .= "<isVoucher>".$this->xmlEntities($this->getData('isVoucher'))."</isVoucher>\n";                
		$xml .= "<validFrom>".$this->xmlEntities($this->getData('validFrom'))."</validFrom>\n";                        
		$xml .= "<validTo>".$this->xmlEntities($this->getData('validTo'))."</validTo>\n";                                
		$xml .= "<isVirtual>".$this->xmlEntities($this->getData('isVirtual'))."</isVirtual>\n";                                        
		$xml .= "<isVisible>".$this->xmlEntities($this->getData('isVisible'))."</isVisible>\n";                                                

		$xml .= "</inventoryItem>\n";
		$xml .= ($isInsert ? "</insertInventoryItem>\n":"</updateInventoryItem>\n"); 
		
		return $xml;
	}    
    
    public function setItem($item){        
		$this->addData(array('code'=>$item->getSku(),
							 'description'=>($item->getData('name') ? $item->getData('name') : '-'),
                             'isActive'=> '',
                             'isInventoried'=> '',
                             'assetAccountUid'=> '',
                             'stockOnHand'=> '' ,
                             'currentValue'=>'',
                             'quantityOnOrder'=>'',
                             'quantityCommitted'=>'',
                             'isBought'=> '',
                             'purchaseExpenseAccountUid'=>'',
                             'minimumStockLevel'=>'',
                             'primarySupplierContactUid'=>'',
                             'defaultReOrderQuantity'=>'',
                             'isSold'=> '', 
                             'saleIncomeAccountUid'=>'',
                             'saleCoSAccountUid'=>'',
                             'sellingPrice'=>$item->getPrice(),
                             'isSellingPriceIncTax'=>'false',
                             'buyingPrice'=>'',
                             'isBuyingPriceIncTax'=>'false',
                             'isVoucher'=>'',
                             'validFrom'=>'',
                             'validTo'=>'',
                             'isVirtual'=>'',
                             'isVisible'=>''
                             ));         
    }
}

