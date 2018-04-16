<?php
/**
 * Copyright © 2017 Shop. All rights reserved.
 */

namespace Shop\Lookbook\Controller\Adminhtml\Lookbook;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Shop\Lookbook\Controller\Adminhtml\Lookbook
{

    public function execute()
    {
        // echo '<pre>';
     
        $modelHelper = $this->_objectManager->create('Shop\Lookbook\Model\Fileuploader');
        $image = $this->getRequest()->getFiles();
        $data = $this->getRequest()->getPostValue();

        // print_r($data);
        // die();


        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {

            if($image['photo']['name'] == '' && $data['photo']['value'] == ''){
                $this->messageManager->addError(__('Please Upload Slide Image.'));
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }

            $data['image'] = 'lookbook/' .$image['photo']['name'];    
            

            $model = $this->_objectManager->create('Shop\Lookbook\Model\Lookbook');


            $inputFilter = new \Zend_Filter_Input(
                [],
                [],
                $data
            );
            $data = $inputFilter->getUnescaped();

            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);

                if ($id != $model->getId()) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                }
            }

            $path = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('lookbook/');

            if(isset($data['photo']['delete'])){
                unlink($this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('/').$data['photo']['value']);
                $data['photo'] = '';
            }else if($image['photo']['name'] != ''){

                // Save Image
                $uploader = $this->_fileUploaderFactory->create(['fileId' => $image['photo']]);

                // $ext = $modelHelper->getAllowedExtensions();
                
                // $explodeExtension = explode(",",$ext);
                // $ext = "'" . implode( "','",$explodeExtension). "'";

                // file_put_contents('./log_'.date("j.n.Y").'.txt',  $ext, FILE_APPEND);

                $uploader->setAllowedExtensions(['jpg','jpeg','gif','png']);
                $uploader->setAllowRenameFiles(true);
                $uploader->save($path);

                $fileName = $uploader->getUploadedFileName();
                if ($fileName) {                    
                    
                    // $temp = explode(".",$fileName);
                    // $newfilename = round(microtime(true)) . '.' . end($temp);
                    
                    $settings_width = $modelHelper->getMaxImageWidth();
                    $thumb_width =  $settings_width / 2;    
                    $thumb_height = 600;

                    // $modelHelper->resizeImage($image,$thumb_width,$thumb_height,75);
                    // $modelHelper->resize($fileName,$width, $height);

                    // file_put_contents('./log_'.date("j.n.Y").'.txt',  $file. ' - ' .$res. ' - ', FILE_APPEND);
                    
                    $data['photo'] = 'lookbook/'.$fileName;                 
                }
                // Save Image
            }else{
                $data['photo'] = $data['photo']['value'];
            }


            $model->setData($data);

            try {

                $model->setData($data);                             
                $session = $this->_objectManager->get('Magento\Backend\Model\Session');
                $session->setPageData($model->getData());

                $model->save();
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');

            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the slide.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }


}
