<?php
namespace Shop\AbandonedCart\Controller\Adminhtml\Listcart;

class Index extends \Magento\Backend\App\Action
{
	protected $_resultPageFactory = false;

	/**
	 * Page factory
	 * 
	 * @var \Magento\Backend\Model\View\Result\Page
	 */
	protected $_resultPage;


	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
	) {
		parent::__construct($context);
		$this->_resultPageFactory = $resultPageFactory;
	}

	public function execute()
	{
		//Call page factory to render layout and page content
		$this->_setPageData();
        return $this->getResultPage();
	}

	/*
	 * Check permission via ACL resource
	 */
	// protected function _isAllowed()
	// {
	// 	return $this->_authorization->isAllowed('Shop_AbandonedCart::listcart_manage');
	// }

    public function getResultPage()
    {
        if (is_null($this->_resultPage)) {
            $this->_resultPage = $this->_resultPageFactory->create();
        }
        return $this->_resultPage;
    }

    protected function _setPageData()
    {
        $resultPage = $this->getResultPage();
        $resultPage->setActiveMenu('Shop_AbandonedCart::listcart');
        $resultPage->getConfig()->getTitle()->prepend((__('Abandoned Orders')));

        //Add bread crumb
        $resultPage->addBreadcrumb(__('Shop'), __('Shop'));
        $resultPage->addBreadcrumb(__('Abandoned Cart'), __('Manage Abandoned Cart'));

        return $this;
    }


}