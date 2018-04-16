<?php 

namespace Shop\Products\Controller\Index;

class Products extends \Magento\Framework\App\Action\Action {

	protected $_pageFactory;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory
	){
		$this->_pageFactory = $pageFactory;	
		parent::__construct($context);
	}

	public function execute(){
		// echo __METHOD__;	
		// $this->getLayout()->createBlock("Shop\Products\Block\Products")->setTemplate("products.phtml")->toHtml();
		return $this->_pageFactory->create();
	} 
}