<?php

class Atd_Tagimage_Adminhtml_TagimageController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('tagimage/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
             $this->loadLayout();
             $this->_setActiveMenu('tagimage/items');
             $this->getLayout()->getBlock('head')->setCanLoadExtJs(true)
             ->addJs('extjs/ext-tree-hidden.js')
             ->removeItem('extjs', 'ext-tree-checkbox.js');
             $this->_addContent($this->getLayout()->createBlock('tagimage/adminhtml_tagimage_edit_images'))
                      ->_addLeft($this->getLayout()->createBlock('tagimage/adminhtml_tagimage_edit_tab_categoryleft'));
            $this->renderLayout();
	}

	public function editAction() {
       
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('tagimage/tagimage')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('tagimage_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('tagimage/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			 $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('tagimage/adminhtml_tagimage_edit'))
				  ->_addLeft($this->getLayout()->createBlock('tagimage/adminhtml_tagimage_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('tagimage')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
            $_model  = Mage::getModel('tagimage/tags_tags');
            Mage::register('tags_data', $_model);
            Mage::register('tags_banner', $_model);
	    $this->_forward('edit');
	}


        public function imageAction() {
        $result = array();
        try {
            $uploader = new Atd_Tagimage_Media_Uploader('image');
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save(
                    Mage::getSingleton('tagimage/config')->getBaseMediaPath()
            );

            $result['url'] = Mage::getSingleton('tagimage/config')->getMediaUrl($result['file']);
            $result['cookie'] = array(
                    'name'     => session_name(),
                    'value'    => $this->_getSession()->getSessionId(),
                    'lifetime' => $this->_getSession()->getCookieLifetime(),
                    'path'     => $this->_getSession()->getCookiePath(),
                    'domain'   => $this->_getSession()->getCookieDomain()
            );
        } catch (Exception $e) {
            $result = array('error'=>$e->getMessage(), 'errorcode'=>$e->getCode());
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }
     
	public function saveAction() {
              if ($data = $this->getRequest()->getPost()) {
                      $tagId = null;
                      $_model = Mage::getModel('tagimage/tagimage');
                      $_imageList = Zend_Json::decode($data['images']);

                         $categories = array();
                         $_strCate = '';
                        if(!empty($data['categories'])){
                              foreach(explode(',', $data['categories']) as $_category){
                                 if(!empty($_category)){
                                     $categories[] = $_category;
                                 }
                              }

                              foreach(array_unique($categories) as $_categorie){
                                    $_strCate.= $_categorie.',';
                              }
                        }
                        $strcate = '';
                        if(!empty($_strCate)){
                             $strcate = substr($_strCate, 0, strlen($_strCate)-1);
                        }
                       $_result = array();
                      foreach($_imageList as $images){
                          if(isset($images['removed'])){
                          
                              if($images['removed']==0){
                                 $_valueid =0;
                                  if(isset ($images['value_id']))
                                  {
                                      $_valueid=$images['value_id'];
                                             $_model->setData($images)
                                                           ->setCategories($strcate)
                                                           ->setTablabel($data['tablabel'])
                                                           ->setContent($data['content'])
                                                           ->setImagecontent($data['imagecontent'])
                                                           ->setId($_valueid);
                                                       
                                  }else{
                                             $_model->setData($images)
                                             ->setTablabel($data['tablabel'])
                                             ->setContent($data['content'])
                                             ->setImagecontent($data['imagecontent'])
                                             ->setCategories($strcate);
                                  }
                                    if ($_model->getCreatedTime == NULL || $_model->getUpdateTime() == NULL) {
                                                $_model->setCreatedTime(now())
                                                        ->setUpdateTime(now());
                                        } else {
                                                $_model->setUpdateTime(now());
                                        }
                                      
                                        $_model->save();
                                         $_result[] = array(
                                                    'url'            => Mage::getSingleton('tagimage/config')->getBaseMediaUrl() . $images['file'],
                                                    'file'          => $images['file'],
                                                    'label'        => $images['label'],
                                                    'position'  => $images['position'],
                                                    'disabled'  => $images['disabled']);
                              }else{
                                  if(isset ($images['value_id']))
                                  {

                                              $_model->setId($images['value_id'])->delete();
                                            	
                                  }else{
                                            
                                  }

                              }
                                   
                          }else{
                              $_valueid =0;
                              if(isset ($images['value_id']))
                              {
                                  $_valueid=$images['value_id'];
                                         $_model->setData($images)
                                                      ->setTablabel($data['tablabel'])
                                                       ->setContent($data['content'])
                                                       ->setImagecontent($data['imagecontent'])
                                                       ->setCategories($strcate)
                                                       ->setId($_valueid);
                                                      

                              }else{
                                         $_model->setData($images)->setCategories($strcate);
                                         $_model->setTablabel($data['tablabel'])
                                                ->setContent($data['content'])
                                                ->setImagecontent($data['imagecontent']);
                                        
                              }
                                if ($_model->getCreatedTime == NULL || $_model->getUpdateTime() == NULL) {
                                            $_model->setCreatedTime(now())
                                                    ->setUpdateTime(now());
                                    } else {
                                            $_model->setUpdateTime(now());
                                    }
                                   
                                    $_model->save();
                                 
                                     $_result[] = array(
                                                'url'            => Mage::getSingleton('tagimage/config')->getBaseMediaUrl() . $images['file'],
                                                'file'          => $images['file'],
                                                'label'        => $images['label'],
                                                'position'  => $images['position'],
                                                'disabled'  => $images['disabled']);
                          }
                        $tagId =   $_model->getId();
                      
                        if(isset($data['pages'] )){
                                 $_page = Mage::getModel('tagimage/page');
                                 $_pageList = '';
                                 foreach($data['pages'] as $page){
                                    $_pageList.=$page.',';
                                  }
                                 $_page ->setId($tagId) ->setPage(substr($_pageList, 0, strlen($_pageList)-1))->save();
                          }
                      }
                     
                         
                       Mage::getSingleton('adminhtml/session')->setImagesData($_result);
                   try {
                                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('tagimage')->__('Item was successfully saved'));
                                Mage::getSingleton('adminhtml/session')->setFormData(false);

                                if ($this->getRequest()->getParam('back')) {
                                        $this->_redirect('*/*/edit', array('id' => $_model->getId()));
                                        return;
                                }
                                $this->_redirect('*/*/');
                                return;
                  } catch (Exception $e) {
                        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                        Mage::getSingleton('adminhtml/session')->setFormData($data);
                        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                        return;
                }
                     
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('tagimage')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
        public function getPageId($pageId){
            $page = Mage::getModel('tagimage/page')->load($pageId);
            if($page->getId()){
                return true;
            }else return false;
        }
        public function categoryAction(){

             $this->loadLayout();
             $this->_setActiveMenu('tagimage/items');
             $this->getLayout()->getBlock('head')->setCanLoadExtJs(true)
             ->addJs('extjs/ext-tree-hidden.js')
             ->removeItem('extjs', 'ext-tree-checkbox.js');
             $this->_addContent($this->getLayout()->createBlock('tagimage/adminhtml_tagimage_edit_images'))
                      ->_addLeft($this->getLayout()->createBlock('tagimage/adminhtml_tagimage_edit_tab_categoryleft'));
            $this->renderLayout();


        }

        public function getImagesAction(){
                $category = $this->getRequest()->getParam('Node');
                $this->getResponse()->setBody($this->getLayout()->createBlock('tagimage/adminhtml_tagimage_edit_images')->toHtml());
        }
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('tagimage/tagimage');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}


    public function TagsAction(){
                $this->loadLayout();
                $this->_setActiveMenu('tagimage/items');
                $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
                $this->renderLayout();
    }

    public function generateAction(){
        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
        $tablename = Mage::getSingleton('core/resource')->getTableName('core_config_data');
        $resultstheme = $connection->fetchAll("SELECT * FROM $tablename WHERE path='design/package/name';");
        $resultskin     = $connection->fetchAll("SELECT * FROM $tablename WHERE path='design/theme/skin';");
        $resultsdefault      = $connection->fetchAll("SELECT * FROM $tablename WHERE path='design/theme/default';");
        $theme =  null;
        foreach($resultstheme as $row) {
            $theme = $row['value'];
         };
       $skin = null;
         foreach($resultskin as $row) {
            $skin = $row['value'];
         };
         $default = null;
         foreach($resultsdefault as $row) {
            $default = $row['value'];
         };


       if($theme=='') $theme ='default';
       if($skin=='') {
           if($default==''){
               $skin='default';
           }else{
               $skin = $default;
           }
       }

       $file  =   Mage::getBaseDir('skin') . DS . 'adminhtml' . DS . 'default'.DS.'default'.DS.'tagimages'.DS.'general.css';
       $fileFrontend  =   Mage::getBaseDir('skin') . DS . 'frontend' . DS .$theme.DS.$skin.DS.'css'.DS.'general.css';
       $enterprise  =   Mage::getBaseDir('skin') . DS . 'frontend' . DS . 'enterprise'.DS.$skin.DS.'css'.DS.'general.css';
       $_helper = Mage::helper('tagimage');
       $width = ((int)$_helper->settings('setheight')-145)/2;
       $widthContainer =  ((int)$_helper->settings('setwidth')+3).'px';
       $heightContainer = ((int)$_helper->settings('setheight')+3).'px';
       $_content = '/*  Cascade Style Sheet */
     .pagination-container{
        height: '.$heightContainer.';
        width:  '.$widthContainer.';
      }
     .pagination-container .slider,
     .pagination-container .galerie{
	width:'.$_helper->getWithFrame().' !important;
	height:'.$_helper->getHeightFrame().'!important;
        position: relative;
      }
      .pagination-container .navigation-button .next{
         position: absolute!important;
         right:0px!important;
         top:'.$width.'px;
      }
      .pagination-container .navigation-button .prev {
        position: absolute!important;
        left:0px!important;
        top:'.$width.'px;
      }';

            if (file_exists($file)) {
                file_put_contents($file, $_content);
                @chmod($file, 0644);
                  Mage::getSingleton('adminhtml/session')->addSuccess('Generate css in admin successful!');
            }
            
            if (file_exists($fileFrontend)) {
                file_put_contents($fileFrontend, $_content);
                @chmod($file, 0644);
                  Mage::getSingleton('adminhtml/session')->addSuccess('Generate css in frontend successful!');
            }
            
           if (file_exists($enterprise)) {
                file_put_contents($enterprise, $_content);
                @chmod($file, 0644);
                  Mage::getSingleton('adminhtml/session')->addSuccess('Generate css in frontend successful!');
            }

               $this->_redirect('adminhtml/system_config/edit/section/tags');
            
    }
    public function massDeleteAction() {
        $tagimageIds = $this->getRequest()->getParam('tagimage');
        if(!is_array($tagimageIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($tagimageIds as $tagimageId) {
                    $tagimage = Mage::getModel('tagimage/tagimage')->load($tagimageId);
                    $tagimage->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($tagimageIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $tagimageIds = $this->getRequest()->getParam('tagimage');
        if(!is_array($tagimageIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($tagimageIds as $tagimageId) {
                    $tagimage = Mage::getSingleton('tagimage/tagimage')
                        ->load($tagimageId)
                        ->setDisabled($this->getRequest()->getParam('disabled'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($tagimageIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'tagimage.csv';
        $content    = $this->getLayout()->createBlock('tagimage/adminhtml_tagimage_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'tagimage.xml';
        $content    = $this->getLayout()->createBlock('tagimage/adminhtml_tagimage_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }


      public function categoriesJsonAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('tagimage/adminhtml_tagimage_edit_tab_category')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }




    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}