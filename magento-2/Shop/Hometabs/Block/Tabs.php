<?php
namespace Shop\Hometabs\Block;
use Magento\Framework\View\Element\Template;

class Tabs extends Template
{
    protected $bestSellerCollection;
    protected $mostViewedCollection;
    protected $wishListHelper;
    protected $_helper;

    protected $_productCollectionFactory;
    protected $_categoryFactory;    

    public function __construct(
        Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory $bestSellerCollectionFactory,
        \Magento\Reports\Model\ResourceModel\Product\CollectionFactory $mostViewedCollectionFactory,
        \Magento\Wishlist\Helper\Data $wishListHelper,
        \Shop\Hometabs\Helper\Data $helper, 

        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        array $data = []
    ) {
        $this->bestSellerCollection = $bestSellerCollectionFactory->create();
        // $this->mostViewedCollection = $mostViewedCollectionFactory->create();
        // $this->wishListHelper = $wishListHelper;
        $this->_helper = $helper;

        $this->_categoryFactory = $categoryFactory;
        $this->_productCollectionFactory = $productCollectionFactory;

        parent::__construct($context, $data);
    }

    /***************************** Tabs *****************************/
    // custom
	public function getEnabled(){
		
    	return $this->_helper->getEnabled();
	}

    public function getCustomCategoryIdByName(){
        
        return $this->_helper->getCustomCategoryIdByName();
    }
   

    // new products
    public function getNewEnabled()
    {
        return $this->_helper->getNewEnabled(); 
    }

    public function getNewTitle()
    {
        return $this->_helper->getNewTitle(); 
    }

    public function getNewDisplayType()
    {
        return $this->_helper->getNewDispalyType(); 
    }

    public function getNewProductCount()
    {
        return $this->_helper->getNewProductCount();  

    }

    // best products
    public function getBestEnabled()
    {
        return $this->_helper->getBestEnabled(); 
    }

    public function getBestTitle()
    {
        return $this->_helper->getBestTitle(); 
    }

    // gift ideas
    public function getGiftIdeasEnabled()
    {
        return $this->_helper->getGiftIdeasEnabled(); 
    }

    public function getGiftIdeasTitle()
    {
        return $this->_helper->getGiftIdeastTitle(); 
    }

  

}    
