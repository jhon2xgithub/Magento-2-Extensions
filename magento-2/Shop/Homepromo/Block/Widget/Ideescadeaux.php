<?php 

namespace Shop\Homepromo\Block\Widget;

class Ideescadeaux extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{

  	protected $_template = 'promo/idees_cadeau.phtml';

    /**
     * Default value for products count that will be shown
     */
    const DEFAULT_PRODUCTS_COUNT = 2;
    const DEFAULT_IMAGE_WIDTH = 240;
    const DEFAULT_IMAGE_HEIGHT = 300;

	protected $_objectManager = null;
    protected $_categoryFactory;
    protected $_category;
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
    protected $_helper;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Block\Product\Context $context,  
        \Magento\Catalog\Model\ProductRepository $_productRepository,     
        array $data = []
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_objectManager = $objectManager;
        $this->_categoryFactory = $categoryFactory;
        $this->_imageHelper = $context->getImageHelper();
        $this->_cartHelper = $context->getCartHelper();
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

    public function getCurrentCategory()
    {
        $category = $this->_objectManager->get('Magento\Framework\Registry')->registry('current_category');
        return $category;
    }

    /**
     * Get category object
     *
     * @return \Magento\Catalog\Model\Category
     */
    public function getCategory($categoryId)
    {
        $this->_category = $this->_categoryFactory->create();
        $this->_category->load($categoryId);
        return $this->_category;
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
     * Get all children categories IDs
     *
     * @param boolean $asArray return result as array instead of comma-separated list of IDs
     * @return array|string
     */
    public function getAllChildren($asArray = false, $categoryId = false)
    {
        if ($this->_category) {
            return $this->_category->getAllChildren($asArray);
        } else {
            return $this->getCategory($categoryId)->getAllChildren($asArray);
        }
    }

    public function getProductCollection()
    {   
        $collection = $this->_objectManager->create('Shop\Homepromo\Model\Ideescadeaux')->getCollection();    
        $ideescadeaux_id = [];  
        foreach($collection->getData() as $item){
            $ideescadeaux_id[] = $item['product_id'];
        }
        $data = implode(',', $ideescadeaux_id);
        $collection = $this->_productCollectionFactory->create();
        if(!empty($ideescadeaux_id)){
            $collection->addFieldToFilter('entity_id',array('in' =>$data));
        }        
        $collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
        $collection->addAttributeToFilter('status',\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
        $collection->setPageSize(self::DEFAULT_PRODUCTS_COUNT);
        return $collection;
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


}