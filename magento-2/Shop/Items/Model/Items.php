<?php 

namespace Shop\Items\Model;

class Items extends \Magento\Framework\Model\AbstractModel{


	public function __construct(
		\Magento\Framework\Model\Context $context,
		array $data = []
	){
		parent::__construct($context, $data);
	} 

	public function _construct(){

	}

}