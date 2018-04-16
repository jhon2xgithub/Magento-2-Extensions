<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */

namespace Shop\Lookbook\Controller\Adminhtml\Lookbook;

class Delete extends \Shop\Lookbook\Controller\Adminhtml\Lookbook
{

    public function execute()
    {

     
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $model = $this->_objectManager->create('Shop\Lookbook\Model\Lookbook');
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('You deleted the item.'));
                $this->_redirect('shop_lookbook/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete item right now. Please review the log and try again.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('shop_lookbook/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a item to delete.'));
        $this->_redirect('shop_lookbook/*/');
    }
}
