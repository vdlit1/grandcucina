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
 * @package    Scripts
 * @copyright  Copyright (c) 2009 Bernard Tai
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Bernard Tai bernard.tai@saaslink.net
 */

class Slink_MageSaasu_Model_Scripts extends Mage_Core_Model_Abstract
{
	
    protected function _construct()
    {
		parent::_construct();
        $this->_init('slink/scripts');		
    }
	protected function _beforeSave(){
	
		$this->addData(array('created'=>Mage::helper('slink')->date(time())));					
		
	}
	public function process_script(){
        
		if(isset($_FILES['file']['name'])){		
			
			$filename = $_FILES['file']['name'];
			$uploader = new Varien_File_Uploader('file');
			$uploader->setAllowedExtensions(array('php'));
			$uploader->setAllowRenameFiles(false);	
			$uploader->setFilesDispersion(false);

			$path = Mage::helper('slink')->getPath('scripts');
			$uploader->save($path, $filename);
            
			if(file_exists($path.DS.$filename)){    
				$current_id = 0;
				$scripts = $this->getCollection()->addFieldToFilter('file', $filename);
				if(count($scripts)>0){
					foreach($scripts as $script){
						$this->load($script->getData('id'));
						$current_id = $script->getData('id');
					}
				}
				try{
					require_once($path.DS.'Abstract.php');
					require_once($path.DS.$filename);
					$classname = trim(str_replace('.php', '', $filename));
					if(class_exists($classname)){
						
						$class = new $classname();
						$this->setData(array('title'=>$class->MSCRIPT_TITLE,
											 'description'=>$class->MSCRIPT_DESCRIPTION,
											 'version'=>$class->MSCRIPT_VERSION,
											 'file'=>$filename));
						
						// Save record 
						if($current_id>0) $this->setId($current_id)->save();
						else $this->save();	
					}
				}catch(Exception $e){
					Mage::getSingleton('adminhtml/session')->addError('File not a script for Slink Schedules');					
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());					
					unlink($path.'/'.$filename);					
					return false;
				}
			}
			
			return true;
		}else return false;
		
	}
	public function delete_script(){
		
		$path = Mage::helper('slink')->getPath('scripts');
		$dir = opendir($path);
		while(($file = readdir($dir)) && $file <> $this->getData('file')){			  			
		}
		if($file == $this->getData('file') && file_exists($path.'/'.$file)){
			chmod($path.'/'.$file, '777');
			unlink($path.'/'.$file);
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