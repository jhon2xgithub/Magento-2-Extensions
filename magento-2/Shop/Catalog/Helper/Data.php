<?php
namespace Shop\Catalog\Helper;
use Magento\Checkout\Model\Cart as CustomerCart; 
use Magento\Catalog\Model\ProductFactory;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $_cart;
    protected $_productRepository;

    /**
     * @var Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;
	   
    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,       
        \Magento\Catalog\Model\ProductRepository $productRepository,
        CustomerCart $cart,
        ProductFactory $productFactory          
    ) {

	
		$this->_cart = $cart;
        $this->_productRepository = $productRepository;
        parent::__construct($context);   
         $this->_productFactory = $productFactory;

        
    }

    public function getBundleProductOptionsData($parent_bundle_id)
    {   
        $productId = $parent_bundle_id;

        $product = $this->_productFactory->create()->load($productId);
       
        $selectionCollection = $product->getTypeInstance(true)
            ->getSelectionsCollection(
                $product->getTypeInstance(true)->getOptionsIds($product),
                $product
            );
 
        return $selectionCollection;      

    }

    public function addBundleToCart($params){   

        $product = $this->_productRepository->getById($params['product']);

        $this->_cart->addProduct($product, $params); 
        $this->_cart->save();


    }
}    