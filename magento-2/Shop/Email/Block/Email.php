<?php 

namespace Shop\Email\Block;

class Email extends \Magento\Framework\View\Element\Template{

	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context
	){
		parent::__construct($context);
	}
}