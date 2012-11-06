<?php
/**
 * Slink Saasu Contact
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
	
class Slink_MageSaasu_Model_Saasu_Contact extends Slink_MageSaasu_Model_Saasu_Abstract{
 
    /* Saasu fields */
    protected $uid;
	protected $lastUpdatedUid;
	protected $salutation;
	protected $givenName; //firstName
	protected $middleInitials; //middleName
	protected $familyName; //lastName
	protected $organisationName;
	protected $organisationAbn;
	protected $organisationWebsite;
	protected $organisationPosition;
	protected $contactId;
	protected $websiteUrl;
	protected $email;
	protected $mainPhone;
	protected $homePhone;
	protected $fax;
	protected $mobilePhone;
	protected $otherPhone;
	protected $tags;
	protected $postalAddress = array();
	protected $otherAddress = array();
	protected $isActive;
	protected $acceptDirectDeposit;
	protected $directDepositAccountName;
	protected $directDepositBsb;
	protected $directDepositAccountNumber;
	protected $acceptCheque;
	protected $chequePayableTo;
	protected $customField1;
	protected $customField2;
	protected $twitterId;
	protected $skypeId;
    
    protected $saleTradingTerms = array();
    protected $purchaseTradingTerms = array();
    protected $defaultSaleDiscount;
    protected $defaultPurchaseDiscount;
    

    public function addPostalAddress($street, $city, $state, $postCode, $country){
        $this->setData('postalAddress', array('street'  =>$street,
                                              'city'    =>$city,
                                              'state'   =>$state,
                                              'postCode'    =>$postCode,
                                              'country' => $country));
        
    }
    public function addOtherAddress($street, $city, $state, $postCode, $country){
        $this->setData('otherAddress', array('street'  =>$street,
                                              'city'    =>$city,
                                              'state'   =>$state,
                                              'postCode'    =>$postCode,
                                              'country' => $country));
        
    }
    public function addPurchaseTradingTerms($type, $interval, $intervalType){
        $this->setData('purchaseTradingTerms', array('type' =>$type,
                                                     'interval'=>$interval,
                                                     'intervalType'=>$intervalType));
        
    }
    public function addSaleTradingTerms($type, $interval, $intervalType){
        $this->setData('saleTradingTerms', array('type' =>$type,
                                                     'interval'=>$interval,
                                                     'intervalType'=>$intervalType));
        
    }
    
    public function get($uid=''){
        if($uid=="") $uid = $this->getData('uid');
        
        if(!($response = $this->curl_send($this->getUrl($uid), '443', '1', false, ''))){        
            return false;
        }
        
        $doc = new DOMDocument();
        $doc->loadXML($response);
		$x = $doc->documentElement;
        
        $contacts = $doc->getElementsByTagName('contact');

        foreach($contacts as $contact){
            
            foreach($contact->childNodes as $field){
                if($field->nodeType==1) $this->setData($field->nodeName, $field->nodeValue);
            }
            $this->setData('uid', $contact->getAttribute('uid'));
            $this->setData('lastUpdatedUid', $contact->getAttribute('lastUpdatedUid'));                
        }           
    }
    
    public function getByName($givenName="", $middleInitials="", $familyName="" ){
        if($code=="") $code = $this->getData('code');   
        
        if(!($response = $this->curl_send($this->listUrl('familyName', $familyName), '443', '1', false, ''))){        
            throw new Exception('No response');
            return false;
        }
        
        $doc = new DOMDocument();
        $doc->loadXML($response);
		$x = $doc->documentElement;
        
        $contacts = $doc->getElementsByTagName('contactListItem'); 
        
        foreach($contacts as $contact){
            $a = array();
            foreach($contact->childNodes as $field){
                if($field->nodeType==1) $a[$field->nodeName] = $field->nodeValue;
            }
            
            if((trim(strtolower($a['familyName'])) == trim(strtolower($familyName))) &&
               (trim(strtolower($a['givenName'])) == trim(strtolower($givenName))) &&               
               (trim(strtolower($a['middleInitials'])) == trim(strtolower($middleInitials)))){
                
                /* Get Contact info by making a separate get call. Returned fields for 'contact' are different to 'contactList' */
                $this->get($a['contactUid']);
    
            }
        }           
        return false;
    }
	
	public function getByContactId($contact_id = 0){
		if(!$contact_id) $contact_id = $this->getData('contactId');
		
		if(!($response = $this->curl_send($this->listUrl('contactId', $contact_id), '443', '1', false, ''))){
            throw new Exception('No response');
            return false;
        }
        
        $doc = new DOMDocument();
        $doc->loadXML($response);
		$x = $doc->documentElement;
        
        $contacts = $doc->getElementsByTagName('contactListItem');
        
        foreach($contacts as $contact){
            $a = array();
            foreach($contact->childNodes as $field){
                if($field->nodeType==1) $a[$field->nodeName] = $field->nodeValue;
            }
            if((trim(strtolower($a['contactID'])) == trim(strtolower($contact_id)))){
                /* Get Contact info by making a separate get call. Returned fields for 'contact' are different to 'contactList' */
                $this->get($a['contactUid']);
				
            }
        }
        return false;
	}
    
    public function getAll(){
        if($code=="") $code = $this->getData('code');   
        
        if(!($response = $this->curl_send($this->listUrl(), '443', '1', false, ''))){        
            throw new Exception('No response');
            return false;
        }      

        $doc = new DOMDocument();
        $doc->loadXML($response);
		$x = $doc->documentElement;
        
        $contacts = $doc->getElementsByTagName('contactListItem'); 
        
        $data=array();
        foreach($contacts as $contact){
            $a = array();
            foreach($contact->childNodes as $field){
                if($field->nodeType==1) $a[$field->nodeName] = $field->nodeValue;
            }
            $data[]=$a;
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
        
        $errors = $doc->getElementsByTagName('error');
        
        if($errors->length>0){
            
            foreach($errors as $node){
                $error = array();
                foreach($node->childNodes as $info){
                    if($info->nodeName=="type") $error['code'] = $info->nodeValue;
                    elseif($info->nodeName=="message") $error['message'] = $info->nodeValue;
                }
                $this->setError($error);
            }
            return false;
        }else{
            
            if($this->getData('uid') && $this->getData('lastUpdatedUid')){
                $action = $doc->getElementsByTagName('updateContactResult')->item(0);
                $this->setData('uid', $action->getAttribute('updatedEntityUid'));
                $this->setData('lastUpdatedUid', $action->getAttribute('lastUpdatedUid')); 
            }else{
                $action = $doc->getElementsByTagName('insertContactResult')->item(0);
                $this->setData('uid', $action->getAttribute('insertedEntityUid'));
                $this->setData('lastUpdatedUid', $action->getAttribute('lastUpdatedUid'));                                                                  
            }
            
            if($this->getData('uid')<>"" && $this->getData('lastUpdatedUid')<>"") return true;                               
        }        
    }
    public function delete($uid=""){
        if(!$uid) $uid = $this->getData('uid');
        
        if(!($response = $this->curl_send($this->getUrl($uid), '443', '1', true, '', true))){
            throw new Exception('No response');
            return false;
        }
        
        return true;
    }
    public function getUrl($uid=''){
        if($uid=='') $uid = $this->getData('uid');
        if(!$uid) {
            throw new Exception('No Item UID specified');            
            return false;
        }
        
        if(!$this->getData('fileuid') || !$this->getData('wsaccesskey')){
            throw new Exception('Saasu credentials not found. This function requires Saasu File UID and Web Services Access Key');                            
        }
        if(substr($this->endpoint, strlen($this->endpoint)-1, 1)=="/"){
            $this->setData('endpoint',  substr($this->endpoint, 0, strlen($this->endpoint)-1));                
        }
        return  $this->endpoint.DS.
                "contact?wsaccesskey=".$this->getData('wsaccesskey')."&fileuid=".$this->getData('fileuid').
                "&uid=".$uid;
        
    }    
    public function listUrl($field='', $value=''){
        if(!$this->getData('fileuid') || !$this->getData('wsaccesskey')){
            throw new Exception('Saasu credentials not found. This function requires Saasu File UID and Web Services Access Key');                            
        }
        
        if(substr($this->endpoint, strlen($this->endpoint)-1, 1)=="/"){
            $this->setData('endpoint', substr($this->endpoint, 0, strlen($this->endpoint)-1));                
        }
        
        return  $this->endpoint.DS.
                "contactList?wsaccesskey=".$this->getData('wsaccesskey')."&fileuid=".$this->getData('fileuid').
                ($field<>"" ? "&SearchFieldName=".$field : "").
                ($field<>"" && $value<>"" ? "&SearchFieldNameBeginsWith=".$value:"");
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
        
		$xml = ($isInsert ? "<insertContact>\n":"<updateContact>\n");
		$xml .= "<contact ";
		$xml .= ($isInsert ? "uid=\"0\">\n" : "uid =\"".$this->xmlEntities($this->getData('uid'))."\" lastUpdatedUid=\"".$this->xmlEntities($this->getData('lastUpdatedUid'))."\" >')\n");
		$xml .= "<salutation>".$this->xmlEntities($this->getData('salutation'))."</salutation>\n";
		$xml .= "<givenName>".$this->xmlEntities($this->getData('givenName'))."</givenName>\n";
		$xml .= "<familyName>".$this->xmlEntities($this->getData('familyName'))."</familyName>\n";
		$xml .= "<middleInitials>".$this->xmlEntities($this->getData('middleInitials'))."</middleInitials>\n";
		$xml .= "<organisationName>".$this->xmlEntities($this->getData('organisationName'))."</organisationName>\n";
		$xml .= "<organisationAbn>".$this->xmlEntities($this->getData('organisationAbn'))."</organisationAbn>\n";
		$xml .= "<organisationWebsite>".$this->xmlEntities($this->getData('organisationWebsite'))."</organisationWebsite>\n";
		$xml .= "<organisationPosition>".$this->xmlEntities($this->getData('organisationPosition'))."</organisationPosition>\n";
		$xml .= "<contactID>".$this->xmlEntities($this->getData('contactId'))."</contactID>\n";
		$xml .= "<websiteUrl>".$this->xmlEntities($this->getData('websiteUrl'))."</websiteUrl>\n";
		$xml .= "<email>".$this->xmlEntities($this->getData('email'))."</email>\n";
		$xml .= "<mainPhone>".$this->xmlEntities($this->getData('mainPhone'))."</mainPhone>\n";
		$xml .= "<homePhone>".$this->xmlEntities($this->getData('homePhone'))."</homePhone>\n";
		$xml .= "<fax>".$this->xmlEntities($this->getData('fax'))."</fax>\n";
		$xml .= "<mobilePhone>".$this->xmlEntities($this->getData('mobilePhone'))."</mobilePhone>\n";
		$xml .= "<otherPhone>".$this->xmlEntities($this->getData('otherPhone'))."</otherPhone>\n";
		$xml .= "<tags>".$this->xmlEntities($this->getData('tags'))."</tags>\n";		
		
		if($p = $this->getData('postalAddress')){
			$xml .= "<postalAddress>\n";
			$xml .= "<street>".$this->xmlEntities($p['street'])."</street>\n";
			$xml .= "<city>".$this->xmlEntities($p['city'])."</city>\n";
			$xml .= "<state>".$this->xmlEntities($p['state'])."</state>\n";
			$xml .= "<postCode>".$this->xmlEntities($p['postCode'])."</postCode>\n";
			$xml .= "<country>".$this->xmlEntities($p['country'])."</country>\n";
			$xml .= "</postalAddress>\n";
		}
		if($o = $this->getData('otherAddress')){
			$xml .= "<otherAddress>\n";
			$xml .= "<street>".$this->xmlEntities($o['street'])."</street>\n";
			$xml .= "<city>".$this->xmlEntities($o['city'])."</city>\n";
			$xml .= "<state>".$this->xmlEntities($o['state'])."</state>\n";
			$xml .= "<postCode>".$this->xmlEntities($o['postCode'])."</postCode>\n";
			$xml .= "<country>".$this->xmlEntities($o['country'])."</country>\n";
			$xml .= "</otherAddress>\n";
		}
		
		$xml .= "<isActive>".$this->xmlEntities(($this->getData('isActive')) ? 'true' : 'false')."</isActive>\n";
		$xml .= "<customField1>".$this->xmlEntities($this->getData('customField1'))."</customField1>\n";
		$xml .= "<customField2>".$this->xmlEntities($this->getData('customField2'))."</customField2>\n";
		$xml .= "</contact>\n";
		$xml .= ($isInsert ? "</insertContact>\n" : "</updateContact>\n");
		
		return $xml;        
    }
    public function setContact($address){
         $this->addData(array('salutation'=>$address->getData('prefix'),
                              'givenName'=>$address->getData('firstname'),
                              'middleInitials'=>$address->getData('middlename'),
                              'familyName'=>$address->getData('lastname'),
                              'organisationName'=>$address->getData('company'),
                              'organisationAbn'=>'', // Does ABN exist in Magento?
                              'organisationWebsite'=>'', // Does website exist?
                              'organisationPosition'=>'',
                              'contactId'=>$address->getData('parent_id'), /* Parent Customer ID */
                              'websiteUrl'=>'',
                              'email'=>'',
                              'mainPhone'=>$address->getData('telephone'),
                              'homePhone'=>'', // no homePhone in Magento
                              'fax'=>$address->getData('fax'),
                              'mobilePhone'=>'', // no mobilePhone in Magento
                              'otherPhone'=>'', //no otherPhone in Magento
                              'tags'=>'', //no tags - should set from parameters
                              'postalAddress'=>'',
                              'otherAddress'=>'',
                              'isActive'=>'true', // $address->getData('is_active'),
                              'acceptDirectDeposit'=>'',
                              'directDepositAccountName'=>'',
                              'directDepositBsb'=>'',
                              'directDepositAccountNumber'=>'',
                              'acceptCheque'=>'',
                              'chequePayableTo'=>'',
                              'customField1'=>'',
                              'customField2'=>'',
                              'twitterId'=>'',
                              'skypeId'=>''
                              ));	 
        $this->addPostalAddress($address->getData('street'), 
                                $address->getData('city'),
                                $address->getData('region'),
                                $address->getData('postcode'),
                                $address->getData('country'));
                 
                            
    }
}

