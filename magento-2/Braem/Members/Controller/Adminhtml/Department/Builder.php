<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Braem\Members\Controller\Adminhtml\Department;

use Braem\Members\Model\DepartmentFactory;
use Magento\Framework\App\RequestInterface;
use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\Registry;

class Builder
{
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $departmentFactory;

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
        DepartmentFactory $departmentFactory,
        Logger $logger,
        Registry $registry
    ) {
        $this->departmentFactory = $departmentFactory;
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
        $departmentId = (int)$request->getParam('entity_id');
        /** @var $product \Magento\Catalog\Model\Product */
        $department = $this->departmentFactory->create();
        $department->setStoreId($request->getParam('store', 0));

        $department->setData('_edit_mode', true);
        if ($departmentId) {
            $department->load($departmentId);
        }

        $departmentDefault = $this->departmentFactory->create();
        $departmentDefault->setStoreId(0);

        if ($departmentId) {
            $departmentDefault->load($departmentId);
        }

        $this->registry->register('department', $department);
        //$this->registry->register('current_department', $department);

        $this->registry->register('braem_members_department', $department);
        $this->registry->register('braem_members_department_default', $departmentDefault);
        return $department;
    }
}
