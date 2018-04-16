<?php
namespace Shop\Homepromo\Block;
use Magento\Framework\View\Element\Template;

class Homepromo extends Template
{
  
	protected $_objectManager = null;

    public function __construct(
        Template\Context $context,   
        \Magento\Framework\ObjectManagerInterface $objectManager,      

        array $data = []
    ) {
        $this->_objectManager = $objectManager;
        parent::__construct($context, $data);
    }



}    