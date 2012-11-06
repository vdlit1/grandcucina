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
 * @package    Schedules
 * @copyright  Copyright (c) 2009 Bernard Tai
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Bernard Tai bernard.tai@saaslink.net
 */

class Slink_MageSaasu_Model_Schedules extends Mage_Core_Model_Abstract
{
	
    protected function _construct()
    {
		parent::_construct();
        $this->_init('slink/schedules');		
    }
	protected function _beforeSave(){
	
		$data =  $this->getData();
		
		/** Validate other input fields - log_email, log_file **/
		if(!isset($data['created_at']) || $data['created_at']==0) {
			$data['created_at'] = Mage::helper('slink')->date(time());						
		}

		$this->addData(array('mhdmd' => $data['mhdmd'],
							 'created_at'=>$data['created_at']));
	}
	
	public function run(){
		if($this->getData('id') > 0){
                                                
            $path = Mage::helper('slink')->getPath('scripts');
            $file = $this->getData('schedule_file');

            require_once($path.DS.'Abstract.php');
            require_once($path.DS.$file);
            
            $classname = str_replace('.php', '', $file);
            if(class_exists($classname)){				
                
                $class = new $classname();
                $class->setName($this->getData('schedule_name').' ( manual run ) ');
                                
                if(isset($class->limit)) $class->setLimit($this->getData('script_limit'));

                $class->setLastRun($this->getData('last_run'));
                $class->execute();
                
                /** Last_run assigned current date/time */
                $this->addData(array('last_run' => Mage::helper('slink')->date(time())));
                $this->save();					
                
                if($this->getData('log_email') <> "")	{
                    $class->email($this->getData('log_email'));
                }
                if($this->getData('log_file') == "1")	{
                    $class->log();
                }
            }

            return true;
			unset($cron);
		}else{
			Mage::getSingleton('core/session')->addError('Aborted - no Schedule ID provided.');
		}
	}
	public function runAll(){
		$schedules = $this->getCollection()->addFieldtoFilter('published', '1')->setOrder('sort_order', 'ASC');
		
		require_once(dirname(__FILE__).DS.'Cronparser.class.php');
		require_once(Mage::helper('slink')->getPath('scripts').DS.'Abstract.php');
		
		$cron = new CronParser();
		
		foreach($schedules as $schedule){
			$cron->calcLastRan(trim($schedule->getData('mhdmd')));
			$cron_run = Mage::helper('slink')->date($cron->getLastRanUnix());
			if($schedule->getData('last_run') < $cron_run){
				$path = Mage::helper('slink')->getPath('scripts');
				$file = $schedule->getData('schedule_file');
				
				require_once($path.'/'.$file);	
				
				$classname = str_replace('.php', '', $file);
				if(class_exists($classname)){				
					$class = new $classname();
					$class->setName($schedule->getData('schedule_name'));
                    
                    echo '<br>last run '.$schedule->getData('last_run');
                    
                    $class->setLastRun($schedule->getData('last_run'));
					if(isset($class->limit)) $class->setLimit($schedule->getData('script_limit'));
					
                    $class->execute();
					$schedule->addData(array('last_run' => Mage::helper('slink')->date(time())));
					$schedule->save();
                    
					if($schedule->getData('log_email') <> "")	$class->email($schedule->getData('log_email'));
					if($schedule->getData('log_file') == "1")	$class->log();
                    
				}					
			}
		}
		return true;
	}
	public function publish($state=0){
		if($this->getData('id')>0){
			$this->addData(array('published'=>($state>0 ? '1' : '0')));
			$this->save();
			return true;
		}else return false;
	}	
}