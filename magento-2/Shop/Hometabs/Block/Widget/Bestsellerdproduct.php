<?php
namespace Shop\Hometabs\Block\Widget;

class Bestsellerdproduct extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    // calling template from view/templates/frontend/widget
	protected $_template = 'widget/bestsellerdproduct.phtml';

    /**
     * Products count
     *
     * @var int
     */
    protected $_productsCount;
    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    protected $_resourceFactory;
    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;
    
	/**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;
    
    /**
     * Image helper
     *
     * @var Magento\Catalog\Helper\Image
     */
    protected $_imageHelper;
     /**
     * @var \Magento\Checkout\Helper\Cart
     */
    protected $_cartHelper;

    protected $_categoryFactory;

    protected $productFactory;
    protected $dataObjectHelper;
    protected $productRepository;
    protected $_helper;

    /**
     * @param Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     */
   public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Reports\Model\ResourceModel\Report\Collection\Factory $resourceFactory,
        \Magento\Reports\Model\Grouped\CollectionFactory $collectionFactory,
        \Magento\Reports\Helper\Data $reportsData,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,                  
        \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory,  
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,    
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Shop\Hometabs\Helper\Data $helper, 
        array $data = []
    ) {
        $this->_resourceFactory = $resourceFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->_reportsData = $reportsData;
        $this->_imageHelper = $context->getImageHelper();
        $this->_cartHelper = $context->getCartHelper();
        $this->_categoryFactory = $categoryFactory;
        $this->productFactory = $productFactory;      
        $this->productRepository = $productRepository;        
        $this->dataObjectHelper = $dataObjectHelper;
        $this->_helper = $helper;

        parent::__construct($context, $data);
    }
 	/**
     * Image helper Object
     */
 	public function imageHelperObj(){
        return $this->_imageHelper;
    }
    /**
     * get featured product collection
     */
    public function getBestsellerProduct(){
     
   
        $limit = $this->getProductLimit();  
        $resourceCollection = $this->_resourceFactory->create('Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection');
    
        $resourceCollection->setPageSize($limit);
        
        return $resourceCollection;

    }

    // custom settings
    public function getCustomEnabled()
    {
        return $this->_helper->getCustomEnabled();
    }

    public function getCustomCategories()
    {
        return $this->_helper->getCustomCategories();
    }

    public function getCustomCategoryIdByName(){
        
        return $this->_helper->getCustomCategoryIdByName();
    }
   
    /**
     * Get the configured limit of productcounts
     * @return int
     */
    public function getProductLimit() {
        if($this->_helper->getBestProductCount()==''){
            return $this->_helper->getBestProductCount(); 
        }
        return $this->_helper->getBestProductCount();
    }

    /**
     * Get the widht of product image
     * @return int
     */
    public function getProductimagewidth() {
        if($this->_helper->getBestImageWidth()==''){
            return $this->_helper->getBestImageWidth();    
        }
        return $this->_helper->getBestImageWidth();
    }
     /**
     * Get the height of product image
     * @return int
     */
    public function getProductimageheight() {
        if($this->_helper->getBestImageHeight()==''){
            return $this->_helper->getBestImageHeight();
        }
        return $this->_helper->getBestImageHeight();
    }

    /**
     * Get the add to cart url
     * @return string
     */
     public function getAddToCartUrl($product, $additional = [])
    {
			return $this->_cartHelper->getAddUrl($product, $additional);
    }
     /**
     * Return HTML block with price
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $priceType
     * @param string $renderZone
     * @param array $arguments
     * @return string
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getProductPriceHtml(
        \Magento\Catalog\Model\Product $product,
        $priceType = null,
        $renderZone = \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST,
        array $arguments = []
    ) {
        if (!isset($arguments['zone'])) {
            $arguments['zone'] = $renderZone;
        }
        $arguments['zone'] = isset($arguments['zone'])
            ? $arguments['zone']
            : $renderZone;
        $arguments['price_id'] = isset($arguments['price_id'])
            ? $arguments['price_id']
            : 'old-price-' . $product->getId() . '-' . $priceType;
        $arguments['include_container'] = isset($arguments['include_container'])
            ? $arguments['include_container']
            : true;
        $arguments['display_minimal_price'] = isset($arguments['display_minimal_price'])
            ? $arguments['display_minimal_price']
            : true;

            /** @var \Magento\Framework\Pricing\Render $priceRender */
        $priceRender = $this->getLayout()->getBlock('product.price.render.default');

        $price = '';
        if ($priceRender) {
            $price = $priceRender->render(
                \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
                $product,
                $arguments
            );
        }
        return $price;
    }

    // get parent ids fron child configurable ids
    public function getConfigChildProductIds($id){
        $product = array();
        if(is_numeric($id)){           
            $product = $this->productRepository->getById($id); 
        }else{
            return;
        } 

        if(!isset($product)){
            return;
        }

        if ($product->getTypeId() != \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            return [];
        }

        $storeId = $this->_storeManager->getStore()->getId();

        $productTypeInstance = $product->getTypeInstance();
        $productTypeInstance->setStoreFilter($storeId, $product);
        $usedProducts = $productTypeInstance->getUsedProducts($product);
        $childrenList = [];       

        foreach ($usedProducts  as $child) {
            $attributes = [];
            $isSaleable = $child->isSaleable();

            //getting in-stock product
            if($isSaleable){
                foreach ($child->getAttributes() as $attribute) {
                    $attrCode = $attribute->getAttributeCode();
                    $value = $child->getDataUsingMethod($attrCode) ?: $child->getData($attrCode);
                    if (null !== $value && $attrCode != 'entity_id') {
                        $attributes[$attrCode] = $value;
                    }
                }

                $attributes['store_id'] = $child->getStoreId();
                $attributes['id'] = $child->getId();
                /** @var \Magento\Catalog\Api\Data\ProductInterface $productDataObject */
                $productDataObject = $this->productFactory->create();
                $this->dataObjectHelper->populateWithArray(
                    $productDataObject,
                    $attributes,
                    '\Magento\Catalog\Api\Data\ProductInterface'
                );
                $childrenList[] = $productDataObject;
            }
        }

        $childData = array();
        foreach($childrenList as $child){
            $childData[] = $child;
        }

        return $childData;
    }
}