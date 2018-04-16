<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Braem\Members\Controller\Adminhtml\Team;

use Braem\Members\Model\TeamFactory;
use Magento\Framework\App\RequestInterface;
use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\Registry;

class Builder
{
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $teamFactory;

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
        TeamFactory $teamFactory,
        Logger $logger,
        Registry $registry
    ) {
        $this->teamFactory = $teamFactory;
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
        $teamId = (int)$request->getParam('entity_id');
        /** @var $product \Magento\Catalog\Model\Product */
        $team = $this->teamFactory->create();
        $team->setStoreId($request->getParam('store', 0));

        $team->setData('_edit_mode', true);
        if ($teamId) {
            $team->load($teamId);
        }

        $teamDefault = $this->teamFactory->create();
        $teamDefault->setStoreId(0);

        if ($teamId) {
            $teamDefault->load($teamId);
        }

        $this->registry->register('team', $team);
        //$this->registry->register('current_team', $team);

        $this->registry->register('braem_members_team', $team);
        $this->registry->register('braem_members_team_default', $teamDefault);
        return $team;
    }
}
