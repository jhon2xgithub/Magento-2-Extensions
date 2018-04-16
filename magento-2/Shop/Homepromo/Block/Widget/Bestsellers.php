<?php
namespace Shop\Homepromo\Block\Widget;

class Bestsellers extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
	protected $_template = 'promo/bestsellers.phtml';

    /**
     * Default value for products count that will be shown
     */
    const DEFAULT_PRODUCTS_COUNT = 2;
    const DEFAULT_IMAGE_WIDTH = 240;
    const DEFAULT_IMAGE_HEIGHT = 300;
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
    protected $_productRepository;
    
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
    protected $productRepository;
    protected $productFactory;
    protected $dataObjectHelper;
    protected $_objectManager = null;

    /**
     * Bestsellers constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Reports\Model\ResourceModel\Report\Collection\Factory $resourceFactory
     * @param \Magento\Reports\Model\Grouped\CollectionFactory $collectionFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \Magento\Reports\Helper\Data $reportsData
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Catalog\Model\ProductRepository $_productRepository
     * @param array $data
     */
   public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Reports\Model\ResourceModel\Report\Collection\Factory $resourceFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, 
        \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory, 
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Catalog\Model\ProductRepository $_productRepository,
        array $data = []
    ) {
        $this->_resourceFactory = $resourceFactory;
        //$this->_collectionFactory = $collectionFactory;
        //$this->_reportsData = $reportsData;
        $this->_imageHelper = $context->getImageHelper();
        $this->_cartHelper = $context->getCartHelper();
        $this->productRepository = $productRepository; 
        $this->productFactory = $productFactory;    
        $this->dataObjectHelper = $dataObjectHelper;
        $this->_objectManager= $objectManager;
        $this->_productRepository = $_productRepository;
        parent::__construct($context, $data);
    }

    public function _getProductUrl($productId){
        $product = $this->_productRepository->getById($productId);
        return $product->getUrlModel()->getUrl($product);
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

       //Get Object Manager Instance
        // $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $collection = $this->_objectManager->create('Shop\Homepromo\Model\Topdesventes')->getCollection();    
        $topdesventes_id = [];  
        foreach($collection->getData() as $item){
            $topdesventes_id[] = $item['product_id'];
        }
        $data = implode(',', $topdesventes_id);


        $resourceCollection = $this->_resourceFactory->create('Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection');
        if(!empty($topdesventes_id)){
            $resourceCollection->addFieldToFilter('product_id',array('in' => $data));
        }
       //var_dump(count($resourceCollection));exit;
       
        $resourceCollection->setPageSize($limit);
        return $resourceCollection;
   }
   
    /**
     * Get the configured limit of products
     * @return int
     */
    public function getProductLimit() {
        if($this->getData('productcount')==''){
            return self::DEFAULT_PRODUCTS_COUNT;
        }
        return $this->getData('productcount');
    }
     /**
     * Get the widht of product image
     * @return int
     */
    public function getProductimagewidth() {
        if($this->getData('imagewidth')==''){
            return self::DEFAULT_IMAGE_WIDTH;
        }
        return $this->getData('imagewidth');
    }
     /**
     * Get the height of product image
     * @return int
     */
    public function getProductimageheight() {
        if($this->getData('imageheight')==''){
            return self::DEFAULT_IMAGE_HEIGHT;
        }
        return $this->getData('imageheight');
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