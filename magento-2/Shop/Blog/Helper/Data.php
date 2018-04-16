<?php
namespace Shop\Blog\Helper;
 
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var array
     */
    protected $_systemConfiguration;
    protected $_directorylist;
    protected $_filesystem;
    protected $_imageFactory;

    public $_mediaUrl;
    protected $productFactory;
	protected $_storger;
    protected $_productRepository;
	   
    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Filesystem\DirectoryList $_directorylist,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Filesystem $filesystem,         
        \Magento\Framework\Image\AdapterFactory $imageFactory,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
       \Magento\Catalog\Model\ProductRepository $productRepository		
           
    ) {
	
		$this->_productRepository = $productRepository;
		$this->_directorylist=$_directorylist;
        $this->_imageFactory = $imageFactory;      
        $this->productFactory = $productFactory;    
        $this->_filesystem = $filesystem;               
        $this->_imageFactory = $imageFactory;     
		$this->_storeManager = $storeManager;
        parent::__construct($context);
        $this->_systemConfiguration = $this->scopeConfig->getValue('shop_blog', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
   		
		$this->_mediaUrl = $this->_storeManager->getStore()->getBaseUrl()."pub/media/";

        
    }


    /**
     * From systen configuration
     *
     */
    public function getEnabled()
    {
        return $this->_systemConfiguration['general']['enable'];
    }

    /**
     * From systen configuration
     *
     */
    public function getFeedUrl()
    {
        return $this->_systemConfiguration['general']['feed_url'];

    }

    /**
     * From system configuration
     *
     */
    public function getLimitPost()
    {
        return $this->_systemConfiguration['general']['limit_post'];
    }

    public function getToplinks()
    {
        // return $this->_systemConfiguration['general']['toplinks'];
    }    

    public function getName()
    {
        return $this->_systemConfiguration['general']['name'];
    }    

    public function getBlogUrl($code)
    {
        $blogUrl = $this->_systemConfiguration['general']['url_prefix'] ?: self::DEFAULT_URL_PREFIX;
        return $this->_getUrl($blogUrl . '/' . $code);
    }


}	