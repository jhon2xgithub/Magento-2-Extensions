<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */

namespace Shop\Lookbook\Controller\Adminhtml\Lookbook;

class Index extends \Shop\Lookbook\Controller\Adminhtml\Lookbook
{
    /**
     * Lookbook list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Shop_Lookbook::lookbook');
        $resultPage->getConfig()->getTitle()->prepend(__('Shop Lookbook'));
        $resultPage->addBreadcrumb(__('Shop'), __('Shop'));
        $resultPage->addBreadcrumb(__('Lookbooks'), __('Lookbooks'));
        return $resultPage;
    }
}
