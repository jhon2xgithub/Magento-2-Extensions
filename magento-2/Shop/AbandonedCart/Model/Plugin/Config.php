<?php

namespace Shop\AbandonedCart\Model\Plugin;

class Config
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Framework\Module\ModuleList\Loader
     */
    protected $_loader;
    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    protected $_writer;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;


    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Module\ModuleList\Loader $loader
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Module\ModuleList\Loader $loader,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->_objectManager = $objectManager;
        $this->_loader = $loader;
        $this->_writer = $configWriter;
        $this->_scopeConfig = $scopeConfig;
    }
    public function aroundSave(\Magento\Config\Model\config $config,\Closure $proceed)
    {
        $ret = $proceed();
        $sectionId = $config->getSection();
        if($sectionId=='abandonedcart' && !$this->_scopeConfig->getValue('abandonedcart/general/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE))
        {
            $this->_writer->save(\Shop\AbandonedCart\Model\Config::ACTIVE,0,$config->getScope(),$config->getScopeId());
        }
        return $ret;
    }

}