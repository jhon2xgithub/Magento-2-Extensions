<?php
namespace Mageplaza\HelloWorld\Controller\Adminhtml\Post;

class Index extends \Magento\Backend\App\Action
{	
	protected $_resultPage;
	protected $resultPageFactory = false;
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
	) {
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
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
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Mageplaza_HelloWorld::post_manage');
	}

    public function getResultPage()
    {
        if (is_null($this->_resultPage)) {
            $this->_resultPage = $this->resultPageFactory->create();
        }
        return $this->_resultPage;
    }

    protected function _setPageData()
    {
        $resultPage = $this->getResultPage();
        $resultPage->setActiveMenu('Mageplaza_HelloWorld::post');
        $resultPage->getConfig()->getTitle()->prepend((__('Posts')));

        //Add bread crumb
        $resultPage->addBreadcrumb(__('Mageplaza'), __('Mageplaza'));
        $resultPage->addBreadcrumb(__('Hello World'), __('Manage Blogs'));

        return $this;
    }


}