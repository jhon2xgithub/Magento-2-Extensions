<?php


namespace Shop\AbandonedCart\Controller\Abandoned;


class Loadquote extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @var \Shop\AbandonedCart\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Shop\AbandonedCart\Helper\Data $helper
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Shop\AbandonedCart\Helper\Data $helper,
        \Psr\Log\LoggerInterface $logger
    )
    {
        parent::__construct($context);
        $this->_objectManager = $context->getObjectManager();
        $this->_helper = $helper;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_logger = $logger;
    }
    public function execute()
    {
       
        $this->_logger->info(__METHOD__);
        $quoteId = (int) $this->getRequest()->getParam('id', false);
      
        $this->_logger->info("quoteid $quoteId");
        if($quoteId) {
            $quote = $this->_objectManager->create('\Magento\Quote\Model\Quote')->load($quoteId);
            $storeId = $quote->getStoreId();
           
            $url = $this->_helper->getConfig(\Shop\AbandonedCart\Model\Config::PAGE,$storeId);
           
            $this->_logger->info("url $url");
            $token = $this->getRequest()->getParam('token', false);

            if(!$token || $token != $quote->getShopAbandonedcartToken())
            {
          
                $this->messageManager->addNotice("Invalid token");
                $this->_redirect($url);
            }
            else {
            
                $coupon = $this->getRequest()->getParam('coupon', false);
                if($coupon)
                {
                    $quote->setCouponCode($coupon);
                }
                $quote->setShopAbandonedcartFlag(1);
                $quote->save();
                
                // if customer id not found set it.
                if(!$quote->getCustomerId())
                {
                    $this->_getCheckoutSession()->setQuoteId($quote->getId());
                }
                if($this->_helper->getConfig(\Shop\AbandonedCart\Model\Config::AUTOLOGIN,$storeId))
                {
                
                    if($quote->getCustomerId())
                    {                      
                        $customerSession = $this->_getCustomerSession();
                        if(!$customerSession->isLoggedIn())
                        {   

                            $customerSession->loginById($quote->getCustomerId());

                        }
                        $this->_redirect('customer/account');
                    }
                }
                $this->_redirect($url);
            }

        }

    }
    protected function _getCustomerSession()
    {
        return $this->_objectManager->get('Magento\Customer\Model\Session');
    }
    protected function _getCheckoutSession()
    {
        return $this->_objectManager->get('Magento\Checkout\Model\Session');
    }
}