<?php
namespace Shop\Hometabs\Helper;

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

    public function getEnabled()
    {
        return $this->_shop_hometabs['general']['enabled'];
    }

    // custom
    public function getCustomEnabled()
    {
        return $this->_shop_hometabs['custom']['enabled'];
    }

    public function getCustomCategories()
    {
        return $this->_shop_hometabs['custom']['categories'];
    }

    public function getCustomCategoryIdByName()
    {   
        $categoryTitle = $this->_shop_hometabs['custom']['category_name'];
        $title =   (count($categoryTitle)>0)?  $categoryTitle: 2;    
        $collection = $this->_categoryFactory->create()->getCollection()->addAttributeToFilter('name',$title)->setPageSize(1);

        if ($collection->getSize()) {
            return $categoryId = $collection->getFirstItem()->getId();
        }
    }    

    /***************************** Tabs *****************************/
    // new products
    public function getNewEnabled()
    {
        return $this->_shop_hometabs['new']['enabled'];
    }

    public function getNewTitle()
    {
        return $this->_shop_hometabs['new']['title'];
    }

    public function getNewProductCount()
    {
        return $this->_shop_hometabs['new']['product_count'];
    }

    public function getNewImageWidth()
    {
        return $this->_shop_hometabs['new']['image_width'];
    }

    public function getNewImageHeight()
    {
        return $this->_shop_hometabs['new']['image_height'];
    }

    public function getNewDispalyType()
    {
        return $this->_shop_hometabs['new']['display_type'];
    }

 
    // best selled
    public function getBestEnabled()
    {
        return $this->_shop_hometabs['best_selled']['enabled'];
    }

    public function getBestTitle()
    {
        return $this->_shop_hometabs['best_selled']['title'];
    }

    public function getBestProductCount()
    {
        return $this->_shop_hometabs['best_selled']['product_count'];
    }

    public function getBestImageWidth()
    {
        return $this->_shop_hometabs['best_selled']['image_width'];
    }

    public function getBestImageHeight()
    {
        return $this->_shop_hometabs['best_selled']['image_height'];
    }

    // gift_ideas
    public function getGiftIdeasEnabled()
    {
        return $this->_shop_hometabs['gift_ideas']['enabled'];
    }

    public function getGiftIdeasTitle()
    {
        return $this->_shop_hometabs['gift_ideas']['title'];
    }

    public function getGiftIdeasProductCount()
    {
        return $this->_shop_hometabs['gift_ideas']['product_count'];
    }

    public function getGiftIdeasImageWidth()
    {
        return $this->_shop_hometabs['gift_ideas']['image_width'];
    }

    public function getGiftIdeasImageHeight()
    {
        return $this->_shop_hometabs['gift_ideas']['image_height'];
    }

    /***************************** Owl Carousel *****************************/
    //New 
    public function getNewOwlCarouselMargin()
    {
        return $this->_shop_owlcarousel['new_tab']['margin'];
    }

    public function getNewOwlCarouselLoop()
    {
        return $this->_shop_owlcarousel['new_tab']['loop'];
    }

     public function getNewOwlCarouselLazyLoad()
    {
        return $this->_shop_owlcarousel['new_tab']['lazy_load'];
    }

    public function getNewOwlCarouselNav()
    {
        return $this->_shop_owlcarousel['new_tab']['nav'];
    }

    //best selled
    public function getBestSelledOwlCarouselMargin()
    {
        return $this->_shop_owlcarousel['best_selled_tab']['margin'];
    }

    public function getBestSelledOwlCarouselLoop()
    {
        return $this->_shop_owlcarousel['best_selled_tab']['loop'];
    }

     public function getBestSelledOwlCarouselLazyLoad()
    {
        return $this->_shop_owlcarousel['best_selled_tab']['lazy_load'];
    }

    public function geBestSelledOwlCarouselNav()
    {
        return $this->_shop_owlcarousel['best_selled_tab']['nav'];
      
    }

    //gift ideas 
    public function getGiftideasOwlCarouselMargin()
    {
        return $this->_shop_owlcarousel['giftideas_tab']['margin'];
    }

    public function getGiftideasOwlCarouselLoop()
    {
        return $this->_shop_owlcarousel['giftideas_tab']['loop'];
    }

     public function getGiftideasOwlCarouselLazyLoad()
    {
        return $this->_shop_owlcarousel['giftideas_tab']['lazy_load'];
    }

    public function gGiftideasOwlCarouselNav()
    {
        return $this->_shop_owlcarousel['giftideas_tab']['nav'];
    }


}    