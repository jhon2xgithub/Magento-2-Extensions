<?php

namespace Shop\Lookbook\Controller\Adminhtml\Lookbook;

class Massdelete extends \Shop\Lookbook\Controller\Adminhtml\Lookbook
{
    public function execute()
    {

        $deleteIds = $this->getRequest()->getParam('id');
        if (!is_array($deleteIds) || empty($deleteIds)) {
            $this->messageManager->addError(__('Please select item(s).'));
        } else {
            try {
                foreach ($deleteIds as $itemId) {
                    $post = $this->_objectManager->get('Shop\Lookbook\Model\Lookbook')->load($itemId);
                    $post->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($itemId))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');    
    }
}