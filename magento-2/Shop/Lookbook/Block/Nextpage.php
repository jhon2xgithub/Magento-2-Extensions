<?php
namespace Shop\Lookbook\Block;

use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Block\Product\ListProduct;

class Nextpage extends \Magento\Framework\View\Element\Template
{   

   /**
     * @var \Crossbrowser\Newsletter\Helper\Data
     */
    protected $_helper;
    protected $_lookbookFactory;
    protected $_productRepository;
    protected $_filesystem ;
    protected $_imageFactory;
    
    protected $_coreRegistry = null;   
    protected $request;
    protected $_productRepositoryFactory;

    protected $productFactory;

    protected $_productCollectionFactory;

    protected $listProductBlock; 



    /**
     * @param \Crossbrowser\Newsletter\Helper\Data $helper
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Shop\Lookbook\Helper\Data $helper,
        \Magento\Framework\View\Element\Template\Context $context,
        \Shop\Lookbook\Model\LookbookFactory $lookbookFactory,
        \Magento\Framework\Filesystem $filesystem,         
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
        ProductFactory $productFactory,

        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Block\Product\ListProduct $listProductBlock,
 
        array $data = [])   
    {   
        $this->_productRepositoryFactory = $productRepositoryFactory;
        $this->_productRepository = $productRepository;
        $this->_helper = $helper;
        $this->_lookbookFactory = $lookbookFactory;
        $this->_filesystem = $filesystem;               
        $this->_imageFactory = $imageFactory; 
        // $this->_coreRegistry = $registry;   
        $this->request = $request;     
        parent::__construct($context, $data);

        $this->_productCollectionFactory = $productCollectionFactory;
        $this->listProductBlock = $listProductBlock;
       
    }    


    public function getProductCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
       $collection = $this->_productCollectionFactory->create()->addAttributeToSelect('*')->load();
        return $collection;
    }
    public function getAddToCartPostParams($product)
    {
        return $this->listProductBlock->getAddToCartPostParams($product);
    }

    public function getProductImage($id){
        // return $product = $this->_productRepositoryFactory->create()->getById($item->getProductId());

        return $this->_productRepositoryFactory->create()->getById($id);
        // $product->getData('image');
        // return $product->getData('thumbnail');
        // $product->getData('small_image');
      
    }


    public function searchparentkey($value,$arr){
       foreach($arr as $key=>$val){
             if(in_array($value,$val)) {
                return $key;
             }
       }

    }

    public function getlookbookid()
    {
        // use 
        $this->request->getParams(); // all params
        return $this->request->getParam('id');
    }

    
    public function getCollection()
    {   
        $objectManager =   \Magento\Framework\App\ObjectManager::getInstance();
        $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
        return $result = $connection->fetchAll("SELECT * FROM `lookbook` WHERE status = 1");
        
        // return $this->_lookbookFactory->create()->getCollection();
    }

    public function getProductById($id)
    {
        return $this->_productRepository->getById($id);
    }
    
    public function getProductBySku($sku)
    {
        // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        // $product = $objectManager->get('Magento\Catalog\Model\Product');
        // if($product->getIdBySku($sku)) {
        //     return $this->_productRepository->get($sku);
        // } else {
        //     return null;
        // }

        //or
        return $this->_productRepository->get($sku);

    }


    public function getSpecialPriceById($id)
    {
        //$id = '21'; //Product ID
        // $product = $this->_productRepository->getById($id);
        // $product->getSpecialPrice();
        // return $specialPrice;

        return $_product = $this->productFactory->create()->load($id)->getPrice();
    }

    public function getSpecialPriceBySku($sku)
    {   
        //$sku = 'test-21'; //Product Sku
        $product = $this->_productRepository->get($sku);
        $product->getSpecialPrice();
        return $specialPrice;
    }

    /**
     * Retrieve image width
     *
     * @return int|null
     */
    public function getImageOriginalWidth($product, $imageId, $attributes = [])
    {
        return $this->_productImageHelper->init($product, $imageId, $attributes)->getWidth();
    }
    
    /**
     * Retrieve image height
     *
     * @return int|null
     */
    public function getImageOriginalHeight($product, $imageId, $attributes = [])
    {
        return $this->_productImageHelper->init($product, $imageId, $attributes)->getHeight();
    }    

    
}