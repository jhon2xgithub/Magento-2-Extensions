<?php

namespace Shop\Homepromo\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $_backendUrl;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * @param \Magento\Framework\App\Helper\Context   $context
     * @param \Magento\Backend\Model\UrlInterface $backendUrl
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);  
        $this->_backendUrl = $backendUrl;
        $this->storeManager = $storeManager;
    }

    /**
     * get products tab Url in admin
     * @return string
     */
    public function getProductsGridUrl()
    {
        return $this->_backendUrl->getUrl('homepromo/homepromo/products', ['_current' => true]);
    }

    /**
     * get giftideas tab Url in admin
     * @return string
     */
    public function getGiftideasGridUrl()
    {
        return $this->_backendUrl->getUrl('homepromo/homepromo/giftideas', ['_current' => true]);
    }

    /**
     * get topdesventes tab Url in admin
     * @return string
     */
    public function getTopdesventesGridUrl()
    {
        return $this->_backendUrl->getUrl('homepromo/homepromo/topdesventes', ['_current' => true]);
    }

    /**
     * get ideescadeaux tab Url in admin
     * @return string
     */
    public function getIdeescadeauxGridUrl()
    {
        return $this->_backendUrl->getUrl('homepromo/homepromo/ideescadeaux', ['_current' => true]);
    }

    /**
     * get ideescadeaux tab Url in admin
     * @return string
     */
    public function getNauveautesGridUrl()
    {
        return $this->_backendUrl->getUrl('homepromo/homepromo/nauveautes', ['_current' => true]);
    }

}
