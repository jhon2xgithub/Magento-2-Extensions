<?php
namespace Shop\Storestock\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $_productRepository;
    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        array $data = []          
    )
    {
        $this->_productRepository = $productRepository;
        parent::__construct($context, $data);
    }
    
    public function getProductById($id)
    {
        return $this->_productRepository->getById($id);
    }
    
    public function getProductBySku($sku)
    {
        return $this->_productRepository->get($sku);
    }

} 