<?php
/**
 * @category   local
 * @package    SavantDegrees
 * @copyright  Copyright (c) 2009 Savant Degrees (http://www.savantdegrees.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Slink_MageSaasu_Block_Admin_Schedules_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
		$weekdays = array('Everyday' =>array('label'=>'Everyday...', 'value'=>'*'),
						  'Mon-Fri Only'=>array('label'=>'Mon-Fri Only', 'value'=>'1-5'),
						  'Sunday'=>array('label'=>'Sunday', 'value'=>'0'),
						  'Monday'=>array('label'=>'Monday', 'value'=>'1'),
						  'Tuesday'=>array('label'=>'Tuesday', 'value'=>'2'),
						  'Wednesday'=>array('label'=>'Wednesday', 'value'=>'3', 'selected'=>'true'),
						  'Thursday'=>array('label'=>'Thursday', 'value'=>'4'), 
						  'Friday'=>array('label'=>'Friday', 'value'=>'5'),
						  'Saturday'=>array('label'=>'Saturday', 'value'=>'6'));
		
		$monthdays = array('*'=>array('value'=>'*', 'label'=>'Every Day...'), 
						   '1'=>array('value'=>'1', 'label'=>'1'),
						   '2','3','4','5','6','7','8','9','10', 
						   '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', 
						   '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31');
		$hours = array('*'=>array('value'=>'*', 'label'=>'Every Hour...'), 
					   '0'=>array('value'=>'0', 'label'=>'0:00 (midnight)'), 
					   '1'=>array('value'=>'1', 'label'=>'1:00'), 
					   '2'=>array('value'=>'2', 'label'=>'2:00'),
					   '3'=>array('value'=>'3', 'label'=>'3:00'), 
					   '4'=>array('value'=>'4', 'label'=>'4:00'), 
					   '5'=>array('value'=>'5', 'label'=>'5:00'),
					   '6'=>array('value'=>'6', 'label'=>'6:00'),
					   '7'=>array('value'=>'7', 'label'=>'7:00'),
					   '8'=>array('value'=>'8', 'label'=>'8:00'),
					   '9'=>array('value'=>'9', 'label'=>'9:00'),
					   '10'=>array('value'=>'10', 'label'=>'10:00'), 
					   '11'=>array('value'=>'11', 'label'=>'11:00'), 
					   '12'=>array('value'=>'12', 'label'=>'12:00 (midday)'),  
					   '13'=>array('value'=>'13', 'label'=>'13:00'), 
					   '14'=>array('value'=>'14', 'label'=>'14:00'), 
					   '15'=>array('value'=>'15', 'label'=>'15:00'), 
					   '16'=>array('value'=>'16', 'label'=>'16:00'), 
					   '17'=>array('value'=>'17', 'label'=>'17:00'), 
					   '18'=>array('value'=>'18', 'label'=>'18:00'), 
					   '19'=>array('value'=>'19', 'label'=>'19:00'), 
					   '20'=>array('value'=>'20', 'label'=>'20:00'), 
					   '21'=>array('value'=>'21', 'label'=>'21:00'), 
					   '22'=>array('value'=>'22', 'label'=>'22:00'), 
					   '23'=>array('value'=>'23', 'label'=>'23:00'));
		$minutes = array('*'=>array('value'=>'*', 'label'=>'Every Minute...'), 
						 '0'=>array('value'=>'0', 'label'=>'00 min past the hour'),
						 '10'=>array('value'=>'10', 'label'=>'10 min past the hour'),
						 '20'=>array('value'=>'20', 'label'=>'20 min past the hour'),
						 '30'=>array('value'=>'30', 'label'=>'30 min past the hour'),
						 '40'=>array('value'=>'40', 'label'=>'40 min past the hour'),
						 '50'=>array('value'=>'50', 'label'=>'50 min past the hour'));
		
	
		$values = array();
		
		if($this->getRequest()->getParam('id') && ($schedule = Mage::registry('current_schedule'))){
			$values = $schedule->getData();			
			
			if($values['created'] < 1) $values['created'] = '0';
			if($values['last_run'] < 1) $values['last_run'] = '0';			
			
			$mhdmd = explode(" ", trim($values['mhdmd']));
			
			if(count($mhdmd)>=5){
				$minute = $mhdmd[0];
				$hour = $mhdmd[1];
				$day = $mhdmd[2];			
				$month = $mhdmd[3];
				$weekday = $mhdmd[4]; 
			}
			$values['wday'] = $weekday;
			$values['whour'] = $hour;
			$values['wminute'] = $minute;
			
			$values['mmonth'] = $month;
			$values['mday'] = $day;
			$values['mhour'] = $hour;
			$values['mminute'] = $minute;		
		}else{
			// Set default values for new schedule here
			$values['published'] = '1';
			$values['created'] = 0;
			$values['last_run'] = 0;
			$values['mhdmd'] = '* * * * *';
			$values['wday'] = '*';
			$values['whour'] = '*';
			$values['wminute'] = '*';
			$values['mmonth'] = '*';
			$values['mday'] = '*';
			$values['mhour'] = '*';
			$values['mminute'] = '*';			
		}	

		$form = new Varien_Data_Form(array(	'id' => 'edit_form',
											'action' => $this->getUrl('*/*/save', array('id'=> $this->getRequest()->getParam('id'))),
											'method' => 'post'
											));
		
		$form->addField('mhdmd', 'hidden', array(	'name'		=> 'mhdmd',
													'required'	=> 'true',
													'value'		=> $values['mhdmd']
												 
													));
        $fieldset = $form->addFieldset('edit_schedule', array('legend' => Mage::helper('slink')->__('Schedule Details')));
		
        $fieldset->addField('schedule_name', 'text', array(
												  'name'      => 'schedule_name',
												  'title'     => Mage::helper('slink')->__('Name'),
												  'label'     => Mage::helper('slink')->__('Name'),
												  'maxlength' => '50',
												  'required'  => true,
												  ));
		$files = $this->toFileOptionsArray();
		$javascript = '';
		$filenames = array();
		foreach($files as $file){
			$filenames[$file['file']] = $file['name'];
			if( (int)$file['use_limit'] < 1 ) $javascript .=	"document.getElementById('script_limit').disabled = false;
																if(document.getElementById('schedule_file').value == '".$file['file']."'){
																document.getElementById('script_limit').value='';									
																document.getElementById('script_limit').disabled= true; } ";
		}
		
        $fieldset->addField('schedule_file', 'select', array(	'name'      => 'schedule_file',
																'title'     => Mage::helper('slink')->__('Script'),
																'label'     => Mage::helper('slink')->__('Script'),
																'maxlength' => '255',
																'required'	=> true,
																'options'=>$filenames,
																'onchange'=>$javascript));

		$fieldset->addField('script_limit', 'text', array(	'name'      => 'script_limit',
															'title'     => Mage::helper('slink')->__('Limit'),
															'label'     => Mage::helper('slink')->__('Limit'),
															'style'		=>'width:50px',
															'class'		=>' validate-number',
															'maxlength' => '255'));
		
		
		$fieldset->addField('published', 'select', array('name'=>'published',
													  'title'=>Mage::helper('slink')->__('Published'),
													  'label'=>Mage::helper('slink')->__('Published'),
														'required'=>true,
														 'options'=>array('0'=>Mage::helper('slink')->__('No'),
																		  '1'=>Mage::helper('slink')->__('Yes'))));
		
		
		$fieldset->addField('log_file', 'select', array('name'=>'log_file',
														'title'=>Mage::helper('slink')->__('Log'),
														'label'=>Mage::helper('slink')->__('Log'),
														'options'=>array('0'=>Mage::helper('slink')->__('No'),
																		'1'=>Mage::helper('slink')->__('Yes')
																		 )));

		$fieldset->addField('log_email', 'text', array('name'=>'log_email',
													   'title'=>Mage::helper('slink')->__('Email'),
													   'label'=>Mage::helper('slink')->__('Email'),
													   'class'=>'validate-email'));
	
		$fieldset->addField('last_run', 'date', array('name'=>'last_run',
													   'title'=>Mage::helper('slink')->__('Last run at'),
													   'label'=>Mage::helper('slink')->__('Last run at'),
														 'format'=>'Y-M-d H:m:s'
                                                      ));
		
		$fieldset->addField('created_at', 'date', array('name'=>'created_at',
													 'title'=>Mage::helper('slink')->__('Created at'),
													 'label'=>Mage::helper('slink')->__('Created at'),
													 'format'=>'Y-M-d H:m:s',
													 'readonly'=>'true'));
		$fieldset->addField('sort_order', 'text', array(	'name'=>'sort_order',
															'title'=>Mage::helper('slink')->__('Position'),
															'label'=>Mage::helper('slink')->__('Position'),
															'style'		=>'width:50px',
															'class'=>'validate-number'));		
		
//		$fieldset->addfield('created', 'hidden', array('name'=>'created'));
		
		
		$frequency = $form->addFieldset('edit_frequency', array('legend'=> 'Frequency'));
		
/*		$frequency->addField('weekly_radio_button', 'radio', array('title'=>'Weekly',
																   'label'=>'Weekly',
																   'onchange'=>"document.getElementById('monthly').style.display='none';
																				document.getElementById('weekly').style.display='block';
																   
																				"));
*/
		$frequency->addField('wday', 'select', array(		'name'=>'wday',
															'label'=>Mage::helper('slink')->__('Day of the Week'),
															'onchange'=>"document.getElementById('mhdmd').value = 
																		 document.getElementById('wminute').value+' '+
																		 document.getElementById('whour').value+' '+
																		 document.getElementById('mday').value+' '+
																		 document.getElementById('mmonth').value+' '+
																		 document.getElementById('wday').value;"))
		
				->setValues($weekdays);
		$timefield = $frequency->addField('whour', 'select', array(		'name'=>'whour',
																		'label'=>Mage::helper('slink')->__('Hour'),
																		'onchange'=>"document.getElementById('mhdmd').value = 
																					document.getElementById('wminute').value+' '+
																					document.getElementById('whour').value+' '+
																					document.getElementById('mday').value+' '+
																					document.getElementById('mmonth').value+' '+
																					document.getElementById('wday').value;"))
				->setValues($hours);
		
		
		$frequency->addField('wminute', 'select', array(	'name'=>'wminute',
															'label'=>Mage::helper('slink')->__('Minute'),
															'onchange'=>" document.getElementById('mhdmd').value = 
																		document.getElementById('wminute').value+' '+
																		document.getElementById('whour').value+' '+
																		document.getElementById('mday').value+' '+
																		document.getElementById('mmonth').value+' '+
																		document.getElementById('wday').value;"))
				->setValues($minutes);
		
//		$monthly = $frequency->addFieldset('monthly', array()); 
		
		$form->addField('mmonth', 'hidden', array(	'name'=>'mmonth'))->setValues(array());
		$form->addField('mday', 'hidden', array(	'name'=>'mday'))->setValues($monthdays);
		$form->addField('mhour', 'hidden', array('name'=>'mhour'))->setValues($hours);
		$form->addField('mminute', 'hidden', array('name'=>'mminute'))->setValues($minutes);

		if(isset($values['schedule_file'])){
			if($files[$values['schedule_file']]['use_limit'] < 1){
				$form->getElement('script_limit')->setDisabled(1);
				$values['script_limit'] = '';				
			}
		}else{
			$first = reset($files);
			if($first['use_limit'] < 1) {
				$form->getElement('script_limit')->setDisabled(1);			
				$values['script_limit'] = '';				
			}
		}
		
							
		$form->setValues($values);
                                                    

		$form->setUseContainer(true);		
        $this->setForm($form);
        return parent::_prepareForm();
    }
	protected function toFileOptionsArray(){
		$scripts = Mage::getModel('slink/scripts')->getCollection()->addFieldToFilter('published', '1');
		
		$options = array();

		$path = Mage::helper('slink')->getPath('scripts');		

		foreach($scripts as $script){		
			require_once(Mage::helper('slink')->getPath('scripts').'/Abstract.php');
			require_once($path.'/'.$script->getData('file'));
						 
			$classname = str_replace('.php', '', $script->getData('file'));
			$class = new $classname();
			
			$use_limit = ((isset($class->limit) && $class->limit > 0) ? '1' : '0');

			$options[$script->getData('file')] = array('name'=>$script->getData('title'),
													   'file'=>$script->getData('file'),
													   'use_limit'=>$use_limit);
		}
		return $options;
	}
	
}