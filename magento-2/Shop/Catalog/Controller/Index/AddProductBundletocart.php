<?php
 
namespace Shop\Catalog\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
class AddProductBundletocart extends \Magento\Framework\App\Action\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory  */
    protected $resultPageFactory;
    

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory     
             
    ) {
        $this->resultPageFactory = $resultPageFactory; 
     
        parent::__construct($context);
    }
    /**
     * Load the page defined in view/frontend/layout/samplenewpage_index_index.xml
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {  
        $post = $this->getRequest()->getParams(); 

        $this->_objectManager->create('\Shop\Catalog\Helper\Data')->addBundleToCart($post);
        
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('checkout/cart/index');
        return $resultRedirect;
    }


    
}