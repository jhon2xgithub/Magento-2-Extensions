<?php


namespace Shop\Reservation\Controller\Product;

class Reserve extends \Magento\Framework\App\Action\Action
{  
    protected $resultPageFactory;

    protected $_resultLayoutFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,  
        \Magento\Framework\View\Result\PageFactory $resultPageFactory      
    ) {
       
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        // $this->_resultLayoutFactory = $resultLayoutFactory;
    }

   
    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {           
        // call class block          
        $layout = $this->_view->getLayout();
        $blockReservation = $layout->createBlock('\Shop\Reservation\Block\Reservation');
        
        $post = $this->getRequest()->getPostValue();

        $product_id = $post['product_id'];
        $_product = $blockReservation->getProductById($product_id); 
          
        // access object directly to get image url  
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();          
        
        $objArray['image_url'] = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $_product->getImage();
        $objArray['product_name']  = $_product->getName(); 
      
        // $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data'); // Instance of Pricing Helper
        // $objArray['price'] = $formattedPrice = $priceHelper->currency($_product->getPrice(), true, false);       
        $objArray['price'] = $_product->getPrice(); 
        // $objArray['appeal'] = $post['appeal'];
        echo json_encode($objArray); 
    
    }

}