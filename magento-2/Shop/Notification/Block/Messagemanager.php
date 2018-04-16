<?php
namespace Shop\Notification\Block;



class Messagemanager extends \Magento\Framework\View\Element\Template
{		
	protected $_messageManager;
	protected $_session;

	protected $_wishlistRepository;

	protected $_productRepository;
	
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context, 
		\Magento\Framework\Message\ManagerInterface $messageManager,
		\Magento\Customer\Model\Session $session,
		\Magento\Wishlist\Model\WishlistFactory $wishlistRepository,
		\Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        array $data = []
	) {
	    parent::__construct($context, $data);
	    $this->_messageManager = $messageManager;
	    $this->_session = $session;

	    $this->_wishlistRepository= $wishlistRepository;
	    $this->_productRepository = $productRepository;
	}

	public function isCustomerLoggedIn()
	{
	    return $this->_session->isLoggedIn();
	}

	public function error() {
		$this->_messageManager->addError(__("Please log in to make wishlist"));
	}

	public function warning() {
		$this->_messageManager->addError(__("Warning"));
	}

	public function notice() {
		$this->_messageManager->addNotice(__("Notice"));
	}

	public function success() {
		$this->_messageManager->addSuccess('Success');
	}


}	 

