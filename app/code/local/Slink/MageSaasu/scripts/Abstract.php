<?php
/**
 * Upload Script Abstract
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
 * @package    
 * @copyright  Copyright (c) 2012 Saaslink
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Bernard Tai bernard@saaslink.com
 */
	
class MSlinkScript{
    
    protected $MSCRIPT_TITLE = "Script Title";
    protected $MSCRIPT_DESCRIPTION = "Script Description";
    protected $MSCRIPT_VERSION  = "1.0";
    
	var $msg = '';
	var $errors = array();
	var $limit = 0;
	var $schedule_name = '';
	var $title = '';
	var $description = '';
	var $version = '';
	var $last_run=0;
	var $_data = null;
	var $footer = '';
	var $from_name = '';
	var $from_email = '';
	
	function __construct()
	{
		$from = Mage::getStoreConfig('trans_email/ident_'.Mage::getStoreConfig('contacts/email/sender_email_identity'));
		
		$this->from_name = $from['name'];
		$this->from_email = $from['email'];
		
		$this->footer = '<br /><br /><br /><br /><small>This email is auto-generated and sent by <a href="mailto:'.$this->from_email.'">'.$this->from_name.'</a>'.
		'. If you have received this email in error, please <a href="mailto:'.$this->from_email.'">reply</a> to inform the sender and destroy this email immediately. '. 
		'<br />Auto-email service provided by Slink <a href="'.$this->saaslink_url.'">'.str_replace('http://', '', $this->saaslink_url).'</a></small> ' ;
				
	}
	
	function log($file=''){
		$file = Mage::helper('slink')->getLogFilePath('schedules');
		
		if(! ($file = fopen($file, 'a+'))){
				$this->setError('Not able to log results');
				return false;
		}
		fputs($file, date('d-m-Y h:i:s A', time())." - Slink Schedules task ( Name: ".$this->title." ) completed successfully.\n");
		fclose($file);

		return true;
	}
	
	/* Function to send standard email - override in inherited scripts */
	function email($email){
		
		$body = $this->email_body_header();
		$body = 'Task:'.$this->MSCRIPT_DESCRIPTION.'<br /><br />';
		
		if(count($this->errors)>0){
			$body .= 'Did not complete. There are errors.<br /><br />';			
		}else{
			$body .= 'Status:<br />Completed successfully.';
		}
		
		$body .= $this->email_body_footer();
		
		if(!$this->email_send($this->email_subject(), $email, $body, $this->email_body_alt($body))){
			$this->setError('Error sending email');			
			return false;
		}

		return true; 
	}
	
	/* Return email subject */
	function email_subject(){
		return 'Slink Scheduled Task - '.$this->schedule_name;
	}
	/* Return email body header */	
	function email_body_header(){
		$text = 'Hi, '.
				'<br><br>This is an automatically generated email report configured to be sent to you.'.
                '<br><br><strong>Task - </strong>'.($this->MSCRIPT_DESCRIPTION ? $this->MSCRIPT_DESCRIPTION : '<no description>').'<br /><br />';
		
		return $text;
	}
	/* Return body in plain text format */
	function email_body_alt($html=""){
		$altbody =  str_replace("<br>", "\r\n", $html);
		$altbody =  str_replace("<br />", "\r\n", $altbody);		
		$altbody =  str_replace("</td>", "\t\t", $altbody);		
		$altbody =  str_replace("</th>", "\t\t", $altbody);				
		$altbody =  str_replace("</tr>", "\r\n", $altbody);				

		$altbody = strip_tags($altbody);
		
		return $altbody;
	}
	/* Return email body footer */	
	function email_body_footer(){
		global $mainframe;
		
		return	$this->footer;
		
	}	
	/* Public function - send email */	
	function email_send($subject='', $emailto, $body, $altbody=''){

		if($mail = Mage::getModel('core/email')){
			$mail->setToName($emailto);
			$mail->setToEmail($emailto);
			$mail->setBody($body);
			$mail->setSubject($subject);
			$mail->setFromEmail($this->from_email);
         	$mail->setFromName($this->from_name);
			$mail->setType('html');
            $mail->send();
		}	

		
		return true;
		/**
		 Other email ids:
		 - ident_sales
		 - ident_support
		 - ident_custom1
		 - ident_customer2
		 **/
	}
	
	function setError($msg){
		$this->msg .= $msg."\r\n";		
		$this->errors[] = $msg;
	}
	function getError(){
		return $this->errors;
	}
	function setMsg($msg){
		$this->msg .= $msg."\r\n";
	}
	function getMsg(){
		return $this->msg;
	}
	function setLimit($limit){
		$this->limit = (int)$limit;
	}
	function setLastRun($last_run){
		$this->last_run = $last_run;
	}
	public function getLastRun(){
        return $this->last_run;
	}
	function setName($name){
		$this->schedule_name = $name;
	}


}