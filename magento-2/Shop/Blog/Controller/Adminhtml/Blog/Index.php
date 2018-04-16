<?php

namespace Shop\Blog\Controller\Adminhtml\Blog;

class Index extends \Shop\Blog\Controller\Adminhtml\Blog
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
        $resultPage->setActiveMenu('Shop_Blog::blog');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Blog'));
        $resultPage->addBreadcrumb(__('Shop'), __('Shop'));
        $resultPage->addBreadcrumb(__('Blog'), __('Blog'));
        return $resultPage;
    }
}
