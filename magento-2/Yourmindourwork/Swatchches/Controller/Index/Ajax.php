<?php
namespace Yourmindourwork\Swatchches\Controller\Index;


class Ajax extends \Magento\Framework\App\Action\Action
{   
    
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
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $product_id = $this->getRequest()->getParam('associate_product');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->create('Magento\Catalog\Model\Product')->load($product_id);
        echo json_encode($product->getData());       

    }

}