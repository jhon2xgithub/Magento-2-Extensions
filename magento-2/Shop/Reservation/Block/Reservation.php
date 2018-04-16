<?php
namespace Shop\Reservation\Block;

class Reservation extends \Magento\Framework\View\Element\Template
{	
	protected $_productRepository;
	protected $_productRepositoryFactory;

	protected $productFactory;
	protected $dataObjectHelper;
	protected $productRepository;

	/**
     * Image helper
     *
     * @var Magento\Catalog\Helper\Image
     */
    protected $_imageHelper;
		
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,	
		\Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productFactory,
		\Magento\Catalog\Api\ProductRepositoryInterface $productRepository, 
		\Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
		
		\Magento\Catalog\Block\Product\Context $productContext,	
		\Magento\Catalog\Model\ProductRepository $_productRepository,	
		array $data = []
	)
	{	
		$this->productFactory = $productFactory;      
	    $this->productRepository = $productRepository;        
	    $this->dataObjectHelper = $dataObjectHelper;

		$this->_imageHelper = $productContext->getImageHelper();
		$this->_productRepository = $_productRepository;
		parent::__construct($context, $data);
	}


	/**
     * 
     */
	public function getProductById($id)
	{
		return $this->_productRepository->getById($id);
	}

	/**
     * 
     */
	public function getProductBySku($sku)
	{
		return $this->_productRepository->get($sku);
	}

	/**
     * 
     */
	public function getProductImageById($item){
		return $this->_productRepositoryFactory->create()->getById($item->getProductId());
	}

	/**
     * Image helper Object
     */
    public function imageHelperObj(){
        return $this->_imageHelper;
    }

    public function getMsg(){

    	return 'Hello';
    }
}