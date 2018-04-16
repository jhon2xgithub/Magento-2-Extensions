<?php 

namespace Shop\Products\Block;

class Products extends \Magento\Framework\View\Element\Template 
{
	protected $_categoryFactory;
	protected $_catalogFactory;
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Catalog\Model\CategoryFactory $categoryFactory,
		\Magento\Catalog\Model\CatalogFactory $catalogFactory,
		array $data = []
	){
		$this->_categoryFactory = $categoryFactory;
		$this->_catalogFactory = $catalogFactory;
		parent::__construct($context, $data);
	}

	public function getCategoryModel($cat_id){	
		$cat = $this->_categoryFactory->create();
		$cat->load($cat_id);
		return $cat;
	}

	public function getProductCollection(){
		$products = $this->_catalogFactory->create();
		$products->getCollection();
		return $products;
	}
}