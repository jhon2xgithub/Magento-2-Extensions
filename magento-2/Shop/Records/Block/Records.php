<?php 

namespace Shop\Records\Block;

class Records extends \Magento\Framework\View\Element\Template
{

	public $_helper;
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Shop\Records\Helper\Data $helper,		
		array $data = []
	){
		$this->_helper = $helper;
		parent::__construct($context, $data);
	}


	public function runEmail(){

		echo __METHOD__;
	}

}