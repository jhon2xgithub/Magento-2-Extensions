<?php 

namespace Shop\Model\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{


	protected $resultPageFactory;
	protected $_postFactory;

	public function __construct(
	    \Magento\Framework\App\Action\Context $context,
	    \Magento\Framework\View\Result\PageFactory $resultPageFactory,
	    \Shop\Model\Model\PostFactory $PostFactory
	   
	)
	{
	    $this->resultPageFactory = $resultPageFactory;
	    $this->_postFactory = $PostFactory;
	    parent::__construct($context);
	}

	public function execute()
	{
		echo '<pre>';
		$post = $this->_postFactory->create();
		$collection = $post->getCollection();
		foreach ($collection as $item) {
			var_dump($item->getData());
			print_r($item->getData());
		}

		exit;
		// return $this->resultPageFactory->create();
	}
}
