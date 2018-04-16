<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */

namespace Shop\Lookbook\Controller\Adminhtml\Lookbook;

class Edit extends \Shop\Lookbook\Controller\Adminhtml\Lookbook
{

    public function execute()
    {

  
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('Shop\Lookbook\Model\Lookbook');
		
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('shop_lookbook/*');
                return;
            }
        }
        // set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
       
		if (!empty($data)) {
            $model->addData($data);
        }
        $this->_coreRegistry->register('current_shop_lookbook_lookbook', $model);
        $this->_initAction();
        //$this->_view->getLayout()->getBlock('lookbook_lookbook_edit');
        $this->_view->renderLayout();
    }
}
