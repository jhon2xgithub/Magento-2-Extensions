<?php


namespace Shop\Storestock\Controller\Product;

class Configurable extends \Magento\Framework\App\Action\Action
{

 
    //
     protected $resultPageFactory;


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
        $this->resultPageFactory = $resultPageFactory;
        // $this->_productRepository = $productRepository;
        parent::__construct($context);
    }

    // get product stocks by product id
    protected function checkAvailability($product_id){
        return $objectManager = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\CatalogInventory\Api\StockStateInterface')->getStockQty($product_id);        
    } 
   
    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {        
        $post = $this->getRequest()->getPostValue();  
        $single_product_id = isset($post['single_product_id'])? $post['single_product_id']:'';
        $data = [];
        if($single_product_id){
            $product_id = $single_product_id;
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $_product = $objectManager->get('Magento\Catalog\Model\Product')->load($product_id);
            $data[0]['sku'] = $_product->getSku(); 
            $data[0]['qty'] = $this->checkAvailability($product_id);     

        }else{
      
            $product_type = $post['product_type']; 
            
            foreach($post['product_id'] as $k=>$id){  

                $data[$k]['sku'] = $post['sku'][$k];
                $data[$k]['qty'] = $this->checkAvailability($post['product_id'][$k]);     
            }   
        }         
       
        try {   
          
            $results['items'] = $data;

        } catch (Exception $e) {
            $results[] = $e->getMessage();
        }     
        
        echo json_encode($results);
    }

}