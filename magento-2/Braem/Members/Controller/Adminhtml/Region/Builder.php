<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Braem\Members\Controller\Adminhtml\Region;

use Braem\Members\Model\RegionFactory;
use Magento\Framework\App\RequestInterface;
use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\Registry;

class Builder
{
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $regionFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $wysiwygConfig;

    /**
     * @param ProductFactory $productFactory
     * @param Logger $logger
     * @param Registry $registry
     */
    public function __construct(
        RegionFactory $regionFactory,
        Logger $logger,
        Registry $registry
    ) {
        $this->regionFactory = $regionFactory;
        $this->logger = $logger;
        $this->registry = $registry;
    }

    /**
     * Build product based on user request
     *
     * @param RequestInterface $request
     * @return \Magento\Catalog\Model\Product
     */
    public function build(RequestInterface $request)
    {
        $regionId = (int)$request->getParam('entity_id');
        /** @var $product \Magento\Catalog\Model\Product */
        $region = $this->regionFactory->create();
        $region->setStoreId($request->getParam('store', 0));

        $region->setData('_edit_mode', true);
        if ($regionId) {
            $region->load($regionId);
        }

        $regionDefault = $this->regionFactory->create();
        $regionDefault->setStoreId(0);

        if ($regionId) {
            $regionDefault->load($regionId);
        }

        $this->registry->register('region', $region);
        $this->registry->register('braem_members_region', $region);
        $this->registry->register('braem_members_region_default', $regionDefault);
        return $region;
    }
}
