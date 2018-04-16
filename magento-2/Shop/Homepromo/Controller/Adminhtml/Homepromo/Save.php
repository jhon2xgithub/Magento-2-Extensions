<?php
namespace Shop\Homepromo\Controller\Adminhtml\Homepromo;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\TestFramework\ErrorLog\Logger;
use Shop\Homepromo\Model\ResourceModel\Homepromo\CollectionFactory;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Backend\Helper\Js
     */
    protected $_jsHelper;
    /**
     * @var \Webspeaks\ProductsGrid\Model\ResourceModel\Contact\CollectionFactory
     */
    protected $_contactCollectionFactory;
    /**
     * \Magento\Backend\Helper\Js $jsHelper
     * @param Action\Context $context
     */
    public function __construct(
        Context $context,
        \Magento\Backend\Helper\Js $jsHelper,
        CollectionFactory $contactCollectionFactory
    ) {
        $this->_jsHelper = $jsHelper;
        $this->_contactCollectionFactory = $contactCollectionFactory;
        parent::__construct($context);
    }
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return true;
    }
    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // includes personal info and products
        $data = $this->getRequest()->getPostValue();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {

            /** @var \Webspeaks\ProductsGrid\Model\Contact $model */
            $model = $this->_objectManager->create('Shop\Homepromo\Model\Homepromo');

            $id = $this->getRequest()->getParam('promo_id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data);

            try {
                $model->save();
                $this->saveTopdesventes($model, $data);
                $this->saveIdeescadeaux($model, $data);
                $this->saveNauveautes($model, $data);

                $this->messageManager->addSuccess(__('You saved this promo.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['promo_id' => $model->getId(), '_current' => true]);               
                }
                return $resultRedirect->setPath('*/*/'); 
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } 
            catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the products.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['promo_id' => $this->getRequest()->getParam('promo_id')]);
        }
       return $resultRedirect->setPath('*/*/');

    }

    public function saveTopdesventes($model, $post) 
    {
       
        if(isset($post['topdesventes'])){  
            $productIds = $this->_jsHelper->decodeGridSerializedInput($post['topdesventes']);
         
         
            try {

                // get old items
                // $collection = $this->_objectManager->create('Shop\Homepromo\Model\Topdesventes')->getCollection();
                $items_collection = []; 
                // foreach($collection as $items){
                //     $items_collection[] = $items->getProductId();
              
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                $connection = $resource->getConnection();

                $sql = "SELECT * FROM topdesventes";
                $result = $connection->fetchAll($sql); 
                // print_r($result);
                foreach ($result as $item) {
                    $items_collection[] = $item['product_id'];  
                }

                $oldProducts = $items_collection; //$model->getProducts($model);
                $newProducts = $productIds;
                $insert = array_diff($newProducts, $oldProducts);
                $delete = array_diff($oldProducts, $newProducts);               
               
                $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');
                $connection = $this->_resources->getConnection();
                $table = $this->_resources->getTableName('topdesventes');           

                if ($delete) {
                              
                    $where = ['promo_id = ?' => (int)$model->getId(), 'product_id IN (?)' => $delete];
                    $connection->delete($table, $where);
                }
                if (!empty($insert)) {
                        
                    $data = [];
                    foreach ($insert as $product_id) {
                         
                        $data[] = ['promo_id' => (int)$model->getId(), 'product_id' => (int)$product_id];
                    }
             
                    $connection->insertMultiple($table, $data);
                }
            } catch (Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the topdesventes.'));
            }
        } 


    }        

    public function saveIdeescadeaux($model, $post)
    {
        if(isset($post['ideescadeaux'])){  
            $productIds = $this->_jsHelper->decodeGridSerializedInput($post['ideescadeaux']);
      
            try {

                // get old items
                // $collection = $this->_objectManager->create('Shop\Homepromo\Model\Topdesventes')->getCollection();
                $items_collection = []; 
                // foreach($collection as $items){
                //     $items_collection[] = $items->getProductId();
              
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                $connection = $resource->getConnection();

                $sql = "SELECT * FROM ideescadeaux";
                $result = $connection->fetchAll($sql); 
                // print_r($result);
                foreach ($result as $item) {
                    $items_collection[] = $item['product_id'];  
                }

                $oldProducts = $items_collection; //$model->getProducts($model);
                $newProducts = $productIds;
                $insert = array_diff($newProducts, $oldProducts);
                $delete = array_diff($oldProducts, $newProducts);
              
                $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');
                $connection = $this->_resources->getConnection();
                $table = $this->_resources->getTableName('ideescadeaux');           


                if ($delete) {
               
                    $where = ['promo_id = ?' => (int)$model->getId(), 'product_id IN (?)' => $delete];
                    $connection->delete($table, $where);
                }
                if ($insert) {
                  
                    $data = [];
                    foreach ($insert as $product_id) {

                        $data[] = ['promo_id' => (int)$model->getId(), 'product_id' => (int)$product_id];
                    }
                    $connection->insertMultiple($table, $data);
                }
            } catch (Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the topdesventes.'));
            }
        }   
    }

    public function saveNauveautes($model, $post)
    {
        if(isset($post['nauveautes'])){  
                 $productIds = $this->_jsHelper->decodeGridSerializedInput($post['nauveautes']);
      
            try {

                // get old items
                // $collection = $this->_objectManager->create('Shop\Homepromo\Model\Topdesventes')->getCollection();
                $items_collection = []; 
                // foreach($collection as $items){
                //     $items_collection[] = $items->getProductId();
              
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                $connection = $resource->getConnection();

                $sql = "SELECT * FROM nauveautes";
                $result = $connection->fetchAll($sql); 
                // print_r($result);
                foreach ($result as $item) {
                    $items_collection[] = $item['product_id'];  
                }

                $oldProducts = $items_collection; //$model->getProducts($model);
                $newProducts = $productIds;
                $insert = array_diff($newProducts, $oldProducts);
                $delete = array_diff($oldProducts, $newProducts);
              
                $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');
                $connection = $this->_resources->getConnection();
                $table = $this->_resources->getTableName('nauveautes');           


                if ($delete) {
               
                    $where = ['promo_id = ?' => (int)$model->getId(), 'product_id IN (?)' => $delete];
                    $connection->delete($table, $where);
                }
                if ($insert) {
                  
                    $data = [];
                    foreach ($insert as $product_id) {

                        $data[] = ['promo_id' => (int)$model->getId(), 'product_id' => (int)$product_id];
                    }
                    $connection->insertMultiple($table, $data);
                }
            } catch (Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the topdesventes.'));
            }
        }   
    }
}