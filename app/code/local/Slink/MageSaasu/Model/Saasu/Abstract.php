<?php
/**
 * Slink Saasu Abstract
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
	
class Slink_MageSaasu_Model_Saasu_Abstract extends Mage_Core_Model_Abstract{

    protected $endpoint='https://secure.saasu.com/webservices/rest/r1/';
    protected $fileuid='';
    protected $wsaccesskey= '';
    
    public function __construct(){

        parent::__construct();
        
        $config = Mage::getStoreConfig('slinksettings/saasu');
        $this->setData(array('fileuid' => $config['fileuid'],
                             'wsaccesskey' => $config['wsaccesskey'],
                             'endpoint' => $config['endpoint']));
    }
    
    public function curl_send($url, $port, $ssl=false, $post=false, $xml='', $delete=false){
        $result = '';
        
        if($ssl) {
            $replace = "https:";
        } else {
            $replace = "http:";	
        }
        
        $url = preg_replace("@^" . $replace ."//@i", "", $url);	
        $host = substr($url, 0, strpos($url, "/"));
        $uri = strstr($url, "/");
        
        if($ssl){
            $host = "https://$host";
        }
        
        
        $contentlength = strlen($xml);
        
        $header = array(
                        "Content-Type: application/x-www-form-urlencoded\r\n",
                        "Content-Type:text/xml; charset = utf-8\r\n",
                        "Content-Length: $contentlength\r\n",					
                        "$xml\r\n");
        
        $handle = curl_init($host.$uri);	
        
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_HEADER, FALSE);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, TRUE);
        
        if($post){	
            curl_setopt($handle, CURLOPT_POST, 1);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $xml);
        }
        if($delete){
            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "DELETE");
        }
        
        curl_setopt($handle, CURLOPT_ENCODING, '');
        curl_setopt($handle, CURLOPT_USERAGENT, 'MAGENTO/SLINK/1.0');
        curl_setopt($handle, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($handle, CURLOPT_TIMEOUT, 120);
        curl_setopt($handle, CURLOPT_MAXREDIRS, 10);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 2);
        
        $response = curl_exec($handle);
        $status = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);
        
        if($status == '200' && $response){
            return $this->xmlify($response);
        }else return false;         
    }
    
    protected function xmlEntities($str) {
        $str = str_replace("\r\n", "\n", $str);
        $str = preg_replace('/[\x00-\x08\x0b\x0c\x0e-\x1f]/', '', $str);
        return htmlspecialchars($str);
    }	
    
    protected function xmlify($input){
        $xml = substr($input, strpos($input, '<?xml'));
        $xml = preg_replace('/&(?!\w{2,6};)/', '&amp;', $xml);
        $xml = preg_replace('/[^\x20-\x7E\x09\x0A\x0D]/', "\n", $xml);
        
        return $xml;		
    }
    public function getError($errors){
        $this->_errors = $errors;
    }
    public function setError(){
        return $this->_errors;
    }

}

