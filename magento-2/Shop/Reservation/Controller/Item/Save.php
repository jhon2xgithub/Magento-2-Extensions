<?php


namespace Shop\Reservation\Controller\Item;

class Save extends \Magento\Framework\App\Action\Action
{  
    protected $resultPageFactory;

    protected $_resultLayoutFactory;
    protected $_moduleFactory;
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
      
        $this->resultPageFactory = $resultPageFactory;  // $this->_resultLayoutFactory = $resultLayoutFactory;
    }


    protected function _getConnection(){
        return \Magento\Framework\App\ObjectManager::getInstance();
    }

    public function getCustomerIdByEmail($email){
        $objectManager = $this->_getConnection();
        $CustomerModel = $objectManager->create('Magento\Customer\Model\Customer');
        $CustomerModel->setWebsiteId(1); 
        $CustomerModel->loadByEmail($email);
        $userId = $CustomerModel->getId();
        return $userId;
    } 

   
    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {    
        $post = $this->getRequest()->getPostValue();
      
        $email = $post['email'];
        $customer_id = $this->getCustomerIdByEmail($email); 

        $objectManager = $this->_getConnection();
        // Load customer
        $customer = $objectManager->create('Magento\Customer\Model\Customer')->load($customer_id); 

        // Load customer session
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');
        $customerSession->setCustomerAsLoggedIn($customer);

        if($customerSession->isLoggedIn()) {

            // saves reserved items   
            try{

                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                $connection = $resource->getConnection();
                             
                //Select Data from table
                $sql = "SELECT * FROM `shop_reservation_post` WHERE email = '{$post['email']}'";
                $result = $connection->fetchAll($sql); 
 
                if( count($result) >= 3){
                   
                    $sql = "SELECT * FROM `shop_reservation_post` WHERE product_id = '{$post['product_id']}' AND email = '{$post['email']}'";
                    $result = $connection->fetchRow($sql); 

                    if($result['product_id']){

                        $update_qty = $result['qty'] + $post['qty'];
                        $update_price = $result['product_price'] * $update_qty;
                        $sql = "UPDATE `shop_reservation_post` SET qty = '{$update_qty}', product_price = '{$update_price}' WHERE product_id = '{$post['product_id']}' AND email = '{$post['email']}'";
                        $connection->query($sql);

                        $results['exceed'] = 0;
                        $results['message'] = 'Qty updated.';
                    }else{
                        $results['exceed'] = 1;
                        $results['message'] = 'You exceed the maximum order to 3';
                    }              

                }else{

                    $sql = "SELECT * FROM `shop_reservation_post` WHERE product_id = '{$post['product_id']}' AND email = '{$post['email']}'";
                    $result = $connection->fetchRow($sql); 
                  
                    if($result['product_id']){
                        $update_qty = $result['qty'] + $post['qty'];
                        $update_price = $result['product_price'] * $update_qty;
                   
                        $sql = "UPDATE `shop_reservation_post` SET qty = '{$update_qty}', product_price = '{$update_price}' WHERE product_id = '{$post['product_id']}' AND email = '{$post['email']}'";
                        $connection->query($sql);
                        $results['exceed'] = 0;
                        $results['message'] = 'Qty updated.';      
                    }else{

                        $model = $this->_objectManager->create('Shop\Reservation\Model\Reservation');            
                        $model->setData($post);             
                        $model->save();
                        $results['exceed'] = 0;
                        $results['message'] = 'You add this product to reserved.';      
                    }     
                }          
                             
              
            } catch (Exception $e) {
                $results['message'] = 'Something went wrong while saving this product.';               
            }    

        }else{

            $results['message'] = "Customer is not logged in";
        }

        echo json_encode($results);
      
    }
    
}    