<?php 
namespace Shop\Records\Helper;

class Email extends \Magento\Framework\App\Helper\AbstractHelper{

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
		array $data = []
	){       
        parent::__construct($context, $data);        
    }

	public function getRecordStatus(){
		print __METHOD__;
	}

	public function getRecordLists(){
		print __METHOD__;
	}
} 