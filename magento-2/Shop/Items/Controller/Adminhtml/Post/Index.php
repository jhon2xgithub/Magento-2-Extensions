<?php 

namespace Shop\Items\Controller\Adminhtml\Post;

class Index extends \Magento\Backend\App\Action{

	protected $_resultPageFactory;
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
	){
		$this->_resultPageFactory = $resultPageFactory;
		parent::__construct($context);
	}

	public function execute(){
		$resultPage = $this->_resultPageFactory->create();
		$resultPage->setActiveMenu('Shop_Items::post');
		return $resultPage;
	}
}
