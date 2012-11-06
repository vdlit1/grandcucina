<?php
/**
 * Config Model
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
 * @category   
 * @package    Slink
 * @copyright  Copyright (c) 2012 Saaslink
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Bernard Tai bernard@saaslink.com
 */

class Slink_MageSaasu_Model_Config_Saasu_Wsaccesskey extends Mage_Core_Model_Config_Data
{
	
    protected function _afterSaveCommit(){

        Mage::register('wsaccesskey', $this->getValue());
        $config = Mage::getStoreConfig('slinksettings');
        
        if((Mage::registry('fileuid')<>"" || Mage::registry('wsaccesskey')<>"")){

                /* Check credentials with a TransactionCategoryList call. If OK, then populate transactioncategory, bankaccount and taxcode tables */
                $accounts = Mage::getModel('slink/saasu_transactioncategories')
                        ->setData('fileuid', Mage::registry('fileuid'))
                        ->setData('wsaccesskey', Mage::registry('wsaccesskey'))
                        ->get();
                
                if(count($accounts)<1){
                    throw new Exception ('Invalid Saasu credentials.');
                    return false;
                } 
                try{
                    $taxcodes = Mage::getModel('slink/saasu_taxcodes')
                                ->setData('fileuid', Mage::registry('fileuid'))
                                ->setData('wsaccesskey', Mage::registry('wsaccesskey'))
                                ->get();
                    $bankaccounts = Mage::getModel('slink/saasu_bankaccounts')
                                ->setData('fileuid', Mage::registry('fileuid'))
                                ->setData('wsaccesskey', Mage::registry('wsaccesskey'))
                                ->get();
                    $contacts = Mage::getModel('slink/saasu_contact')
                                ->setData('fileuid', Mage::registry('fileuid'))
                                ->setData('wsaccesskey', Mage::registry('wsaccesskey'))
                                ->getAll();
                    
                    if(count($accounts)<1) throw new Exception("No accounts found. Please set up Saasu accounts");
                    if(count($taxcodes)<1) throw new Exception("No tax codes found. Please set up Saasu tax codes");
                    if(count($bankaccounts)<1) throw new Exception("No bank accounts found. Please set up Saasu bank accounts");                
                    if(count($contacts)<1) throw new Exception("No bank accounts found. Please set up Saasu contacts");                                    

                    Mage::getSingleton('core/resource')->getConnection('core_write')->query('DELETE from slink_transaction_category WHERE 1');            
                    foreach($accounts as $account){
                        Mage::getSingleton('slink/transactioncategories')
                        ->setData($account)
                        ->setData('entity_uid', $account['transactionCategoryUid'])
                        ->save();
                        
                    }
                    
                    Mage::getSingleton('core/resource')->getConnection('core_write')->query('DELETE from slink_tax_code WHERE 1');
                    foreach($taxcodes as $taxcode){
                        Mage::getSingleton('slink/taxcodes')
                        ->setData($taxcode)
                        ->setData('entity_uid', $taxcode['taxCodeUid'])                    
                        ->save();
                    }
                    
                    Mage::getSingleton('core/resource')->getConnection('core_write')->query('DELETE from slink_bank_account WHERE 1');            
                    foreach($bankaccounts as $bankaccount){
                        Mage::getSingleton('slink/bankaccounts')
                        ->setData($bankaccount)
                        ->setData('entity_uid', $bankaccount['bankAccountUid'])                    
                        ->setData('name', $bankaccount['displayName'])
                        ->save();                    
                    }
                    Mage::getSingleton('core/resource')->getConnection('core_write')->query('DELETE from slink_config_contact WHERE 1');            
                    foreach($contacts as $contact){
                        Mage::getSingleton('slink/configcontacts')
                        ->setData($contact)
                        ->setData('entity_uid', $contact['contactUid'])                    
                        ->setData('name', $contact['givenName'].' '.$contact['familyName'])
                        ->save();                    
                    }                    
                
                }catch(Exception $e){
                    throw new Exception($e->getMessage());
                }
       
        }
        parent::_afterSave();
    }
}