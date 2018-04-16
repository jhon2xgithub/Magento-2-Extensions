<?php
namespace Shop\Instagram\Block;

class Instagram extends \Magento\Framework\View\Element\Template
{   
    
	protected $_helper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,   
        \Shop\Instagram\Helper\Data $helper, 
        array $data = [])
    {    
    	$this->_helper = $helper;
        parent::__construct($context, $data);        
    }

    public function getEnabled()
    {
    	return $this->_helper->getEnabled();
    }

    public function getAccessToken()
    {
        return $this->_helper->getAccessToken();
    }

    public function getInstagramUrl()
    {
        return $this->_helper->getInstagramUrl();
    }

    public function getUserId()
    {
        return $this->_helper->getUserId();
    }

    public function getTag()
    {
        return $this->_helper->getTag();
    }

    public function getShowPost()
    {
        return $this->_helper->getShowPost();
    }

    public function getshowMore()
    {
        return $this->_helper->getshowMore();
    }

}
