<?php
namespace Shop\Reservation\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $_shop_hometabs;   
    protected $_shop_owlcarousel;   
    protected $_categoryFactory;
	   
    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory             
    ) {	      

        $this->_categoryFactory = $categoryFactory;
        parent::__construct($context);   
        $this->_shop_hometabs = $this->scopeConfig->getValue('shop_hometabs', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->_shop_owlcarousel = $this->scopeConfig->getValue('shop_owlcarousel', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}    

