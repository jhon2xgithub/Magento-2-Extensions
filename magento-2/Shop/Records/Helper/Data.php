<?php 
namespace Shop\Records\Helper;

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
	protected $_storeManager;
	protected $_productRepository;

    protected $_scopeConfig;
	protected $inlineTranslation;
    protected $_transportBuilder;
    protected $temp_id;
    const XML_PATH_EMAIL_TEMPLATE_FIELD  = 'system/shopsmtp/sendemail';
	       
    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Filesystem\DirectoryList $_directorylist,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Filesystem $filesystem,         
        \Magento\Framework\Image\AdapterFactory $imageFactory,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,  
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,    
        array $data = []) 
    {	
		$this->_productRepository = $productRepository;
		$this->_directorylist=$_directorylist;
        $this->_imageFactory = $imageFactory;      
        $this->productFactory = $productFactory;    
        $this->_filesystem = $filesystem;               
        $this->_imageFactory = $imageFactory;     
		$this->_storeManager = $storeManager;        
		$this->_mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);	    
		$this->_scopeConfig = $context;
        $this->inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder; 
		parent::__construct($context, $data);
    }


	

    public function someMethod()
    {
        return $this->_storeManager->getStore()->getStoreId();

        // $transport = $this->_transportBuilder->setTemplateIdentifier('abandonedcart_general_template1')
        //     ->setTemplateOptions(['area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE, 'store' => 1])
        //     ->setTemplateVars($vars)
        //     ->setFrom('junsayjohn32@gmail.com')
        //     ->addTo('junsayjohn4@gmail.com', 'jhon2x')
        //     ->getTransport();
        // $transport->sendMessage();
    }
}