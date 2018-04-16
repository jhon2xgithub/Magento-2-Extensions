<?php
namespace Shop\Instagram\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $_systemConfiguration;
   
	   
    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
              
    ) {	

      
        parent::__construct($context);   
        $this->_systemConfiguration = $this->scopeConfig->getValue('shop_instagram', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getEnabled()
    {
        return $this->_systemConfiguration['general']['enable'];
    }

    public function getAccessToken()
    {
        return $this->_systemConfiguration['general']['access_token'];
    }

    public function getInstagramUrl()
    {
        return $this->_systemConfiguration['general']['url'];
    }

    public function getUserId()
    {
        return $this->_systemConfiguration['general']['user_id'];
    }

    public function getTag()
    {
        return $this->_systemConfiguration['general']['tag'];
    }

    public function getShowPost()
    {
        return $this->_systemConfiguration['general']['show_post'];
    }

    public function getshowMore()
    {
        return $this->_systemConfiguration['general']['show_more'];
    }


}    