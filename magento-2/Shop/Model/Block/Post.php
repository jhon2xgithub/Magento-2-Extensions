<?php 

namespace Shop\Model\Block;

class Post extends \Magento\Framework\View\Element\Template
{
	protected $_postFactory;
	public function _construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Shop\Model\Model\PostFactory $PostFactory
	){

		$this->_postFactory = $PostFactory;
		parent::_construct($context);
	}

	public function _prepareLayout(){
		// to create object Model
		$post = $this->_postFactory->create();
		$collection = $post->getCollection();
		foreach ($collection as $item) {
			var_dump($item->getData());
		}

		exit;
	}

	public function test(){
		echo __METHOD__;
	}
}