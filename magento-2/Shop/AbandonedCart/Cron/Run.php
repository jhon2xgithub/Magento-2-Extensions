 <?php 
namespace Shop\AbandonedCart\Cron;

class Run {
 
    protected $days;
    protected $maxtimes;
    protected $sendcoupon;
    protected $firstdate;
    protected $unit;
    protected $customergroups;
    protected $smtpTag;
    protected $couponamount;
    protected $couponexpiredays;
    protected $coupontype;
    protected $couponlength;
    protected $couponlabel;
    protected $sendcoupondays;

    /**
     * @var \Magento\Store\Model\StoreManager
     */
    protected $_storeManager;
    /**
     * @var \Shop\AbandonedCart\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    protected $_stockRegistry;
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;
    /**
     * @var \Psr\Log\LoggerInterface
     */  
    protected $_logger;

    /**
     * @param \Magento\Store\Model\StoreManager $storeManager
     * @param \Shop\AbandonedCart\Helper\Data $helper
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Psr\Log\LoggerInterface $logger
     */

    public function __construct(
        \Magento\Store\Model\StoreManager $storeManager,
        \Shop\AbandonedCart\Helper\Data $helper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Psr\Log\LoggerInterface $logger       
    )
    {

        $this->_storeManager    = $storeManager;
        $this->_helper          = $helper;
        $this->_objectManager   = $objectManager;
        $this->_transportBuilder= $transportBuilder;
        $this->_stockRegistry   = $stockRegistry;
        $this->_logger          = $logger;

    }    

    public function execute()
    {    
        foreach($this->_storeManager->getStores() as $storeId => $val)
        {
            $this->_storeManager->setCurrentStore($storeId);
            if($this->_helper->getConfig(\Shop\AbandonedCart\Model\Config::ACTIVE)) {             
                $this->_proccess($storeId);
            }
        }
    }

    public function cleanAbandonedCartExpiredCoupons()
    {
        $allStores = $this->_storeManager->getStores();
        foreach ($allStores as $storeId => $val) {  
            if ($this->_helper->getConfig(Shop_AbandonedCart_Model_Config::ACTIVE, $storeId)) {
                $this->_cleanCoupons($storeId);
            }
        }
    }

    /**
     * @param $storeId
     */
    protected function _proccess($storeId)
    {

        // choose days
        $this->days = array(
            0 => $this->_helper->getConfig(\Shop\AbandonedCart\Model\Config::DAYS_1, $storeId)
        );

        $this->maxtimes = $this->_helper->getConfig(\Shop\AbandonedCart\Model\Config::MAXTIMES, $storeId) + 1;      
        $this->firstdate = $this->_helper->getConfig(\Shop\AbandonedCart\Model\Config::FIRST_DATE, $storeId);        
        $this->unit = $this->_helper->getConfig(\Shop\AbandonedCart\Model\Config::UNIT, $storeId);       
        $this->customergroups   = explode(",", $this->_helper->getConfig(\Shop\AbandonedCart\Model\Config::CUSTOMER_GROUPS, $storeId));
           
        if($this->_helper->getConfig(\Shop\AbandonedCart\Model\Config::CUSTOMER_GROUPS, $storeId))
        {
            $this->customergroups = explode(",", $this->_helper->getConfig(\Shop\AbandonedCart\Model\Config::CUSTOMER_GROUPS, $storeId));
        }
        else {
            $this->customergroups = array();
        }
        
        // iterates one time for each mail number
        for ($run = 0; $run < $this->maxtimes; $run++) {
            if (!$this->days[$run]) {
                return;
            }
           
            $this->_processRun($run, $storeId);

        }
    }
    protected function _processRun($run, $storeId)
    {     
        // subtract days from latest run to get difference from the actual abandon date of the cart
        $diff = $this->days[$run];
        if ($run == 1 && $this->unit == \Shop\AbandonedCart\Model\Config::IN_HOURS) {
         
            $diff -= $this->days[0] / 24;
        } elseif ($run != 0) {
        
            $diff -= $this->days[$run - 1];
        }
        
        $this->_getIntervalUnitSql($diff, 'DAY');
    
        // set the top date of the carts to get
        $expr = sprintf('DATE_SUB(now(), %s)', $this->_getIntervalUnitSql($diff, 'DAY'));
     
        if ($run == 0 && $this->unit == \Shop\AbandonedCart\Model\Config::IN_HOURS) {
            $expr = sprintf('DATE_SUB(now(), %s)', $this->_getIntervalUnitSql($diff, 'HOUR'));
        }
        
        $from = new \Zend_Db_Expr($expr);          

        // get collection of abandoned carts with cart_counter == $run
        $collection = $this->_objectManager->create('Magento\Reports\Model\ResourceModel\Quote\Collection');
        $collection->addFieldToFilter('items_count', array('neq' => '0'))
            ->addFieldToFilter('main_table.is_active', '1')
            ->addFieldToFilter('main_table.store_id', array('eq' => $storeId))
            ->addSubtotal($storeId)
            ->setOrder('updated_at');  

        $collection->addFieldToFilter('main_table.converted_at', array(array('null' => true), $this->_getSuggestedZeroDate()))
            ->addFieldToFilter('main_table.updated_at', array('to' => $from, 'from' => $this->firstdate))
            ->addFieldToFilter('main_table.shop_abandonedcart_counter', array('eq' => $run));    

        $collection->addFieldToFilter('main_table.customer_email', array('neq' => ''));

        if (count($this->customergroups)) {
            $collection->addFieldToFilter('main_table.customer_group_id', array('in' => $this->customergroups));
        }
        
        // for each cart of the current run
        foreach ($collection as $quote) {
            
            $this->_proccessCollection($quote, $storeId);         

            if (count($quote->getAllVisibleItems()) < 1) { 
                
                $quote2 = $this->_objectManager->create('\Magento\Quote\Model\Quote')->loadByIdWithoutStore($quote->getId());
                $quote2->setShopAbandonedcartCounter($quote2->getShopAbandonedcartCounter() + 1);
                $quote2->save();
                continue;
            }
            // check if they are any order from the customer with date >=
            $collection2 = $this->_objectManager->create('\Magento\Reports\Model\ResourceModel\Quote\Collection');
            $collection2->addFieldToFilter('main_table.is_active', '0')
                ->addFieldToFilter('main_table.reserved_order_id', array('neq' => 'NULL'))
                ->addFieldToFilter('main_table.customer_email', array('eq' => $quote->getCustomerEmail()))
                ->addFieldToFilter('main_table.updated_at', array('from' => $quote->getUpdatedAt()));
       
            if ($collection2->getSize()) {
                continue;
            }
            $token = md5(rand(0, 9999999));        
           
            $url = $this->_storeManager->getStore($storeId)->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK) . 'abandonedcart/abandoned/loadquote?id=' . $quote->getEntityId() . '&token=' . $token;       

            $senderid = $this->_helper->getConfig(\Shop\AbandonedCart\Model\Config::SENDER, $storeId);
            $sender = array(
                'name'  => $this->_helper->getConfig("trans_email/ident_$senderid/name", $storeId),
                'email' => $this->_helper->getConfig("trans_email/ident_$senderid/email", $storeId)
                );

            $email  = $quote->getCustomerEmail();            
            $name   = $quote->getCustomerFirstname() . ' ' . $quote->getCustomerLastname();
            $quote2 = $this->_objectManager->create('\Magento\Quote\Model\Quote')->loadByIdWithoutStore($quote->getId());
        
            //if hour is set for first run calculates hours since cart was created else calculates days
            $today = idate('U', strtotime(date('Y-m-d H:i:s')));
            $updatedAt = idate('U', strtotime($quote2->getUpdatedAt()));
            $updatedAtDiff = ($today - $updatedAt) / 60 / 60 / 24;
            if ($this->unit == \Shop\AbandonedCart\Model\Config::IN_HOURS && $run == 0) {
                $updatedAtDiff = ($today - $updatedAt) / 60 / 60;
            }
    
            // if days have passed proceed to send mail
            if ($updatedAtDiff >= $diff) {
        
                $mailSubject = $this->_getMailSubject($run, $storeId);
                $templateId = $this->_getTemplateId($run, $storeId); // ex abandonedcart_general_template1         
                        
                $vars = array(
                    'quote' => $quote, 
                    'url' => $url, 
                    'subject'=>$mailSubject,
                    'name' => $name
                );
                
                // backend email sender
                $transport = $this->_transportBuilder->setTemplateIdentifier($templateId)
                    ->setTemplateOptions(['area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE, 'store' => $storeId])
                    ->setTemplateVars($vars)
                    ->setFrom($sender)
                    ->addTo($email, $name)
                    ->getTransport();
                $transport->sendMessage();
                
                $quote2->setShopAbandonedcartCounter($quote2->getShopAbandonedcartCounter() + 1);
                $quote2->setShopAbandonedcartToken($token);
                $quote2->save();         
            }
            
        }
    }

    protected function _proccessCollection($quote, $storeId)
    {
        foreach ($quote->getAllVisibleItems() as $item) {    
            $removeFromQuote = false;
            $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($item->getProductId());
            if (!$product || $product->getStatus() == \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED) {

                $removeFromQuote = true;
            }
            $stock = null;
            if ($product->getTypeId() == 'configurable') {
           
                $simpleProductId = $this->_objectManager->create('Magento\Catalog\Model\Product')->getIdBySku($item->getSku());
                $simpleProduct = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($simpleProductId);             
                $stock = $simpleProduct->getStockItem();          
                // $stockQty = $stock->getQty();
            } elseif ($product->getTypeId() == 'bundle') {
          
                $options = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
                $bundled_product = $this->_objectManager->create('\Magento\Catalog\Model\Product')->load($product->getId());
                $selectionCollection = $bundled_product->getTypeInstance(true)->getSelectionsCollection(
                    $bundled_product->getTypeInstance(true)->getOptionsIds($bundled_product), $bundled_product
                );
                $stockQty = -1;
                foreach ($selectionCollection as $option) {
                    foreach ($options['bundle_options'] as $bundle) {
                        if ($bundle['value'][0]['title'] == $option->getName()) {
                            $label = $bundle['label'];
                            $qty = $bundle['value'][0]['qty'];
                            if ($stockQty == -1 || $stockQty > $qty) {
                                $stockQty = $qty;
                            }
                        }
                    }
                }

            } else {
             
                $stock =  $this->_stockRegistry->getStockItem($product->getGetId(),$storeId);//$product->getStockItem();
         
                $stockQty = $stock->getQty();
            }
       
            if ((is_object($stock) && ($stock->getManageStock() || ($stock->getUseConfigManageStock() && $this->_helper->getConfig('cataloginventory/item_options/manage_stock', $quote->getStoreId())))
                )
                && $stockQty < $item->getQty()
            ) {
             
                $removeFromQuote = true;
            }
            if ($removeFromQuote) {
            
                $quote->removeItem($item->getId());
            }
        }
    }    

    /**
     * @param $interval
     * @param $this->unit
     * @return string
     */
    function _getIntervalUnitSql($interval, $unit)
    {
        return sprintf('INTERVAL %d %s', $interval, $unit);
    }

    /**
     * @return string
     */
    function _getSuggestedZeroDate()
    {
        return '0000-00-00 00:00:00';
    }
    /**
     * @param $currentCount
     * @param $store
     * @return mixed|null
     */
    protected function _getMailSubject($currentCount, $store)
    {

        $ret = NULL;
        switch ($currentCount) {
            case 0:            
                $ret = $this->_helper->getConfig(\Shop\AbandonedCart\Model\Config::FIRST_SUBJECT, $store);             
                break;            
        }
        return $ret;
    }

    /**
     * @param $currentCount
     * @return mixed
     */
    protected function _getTemplateId($currentCount, $store)
    {

        $ret = NULL;
        switch ($currentCount) {
            case 0:            
                $ret = $this->_helper->getConfig(\Shop\AbandonedCart\Model\Config::FIRST_EMAIL_TEMPLATE_XML_PATH, $store);           
                break;            
        }
        return $ret;
    }

    protected function _cleanCoupons($store)
    {
        $today = date('Y-m-d');
        $collection = $this->_objectManager->create('Magento\SalesRule\Model\Rule')
            ->getCollection()
            ->addFieldToFilter('name', ['like' => 'Abandoned coupon%'])
            ->addFieldToFilter('to_date', ['lt' => $today]);

        foreach ($collection as $toDelete) {
            $toDelete->delete();
        }
    }   
}	