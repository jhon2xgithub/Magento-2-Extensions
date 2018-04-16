<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Braem\Members\Setup;

use Magento\Eav\Model\Entity\Setup\Context;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;


class MembersSetup extends \Magento\Eav\Setup\EavSetup
{
    /**
     * @var ScopeConfigInterface
     */
    protected $config;

    /**
     * @var EncryptorInterface
     */
    protected $encryptor;

    /**
     * @param ModuleDataSetupInterface $setup
     * @param Context $context
     * @param CacheInterface $cache
     * @param CollectionFactory $attrGroupCollectionFactory
     * @param ScopeConfigInterface $config
     */
    public function __construct(
        ModuleDataSetupInterface $setup,
        Context $context,
        CacheInterface $cache,
        CollectionFactory $attrGroupCollectionFactory,
        ScopeConfigInterface $config
    ) {
        $this->config = $config;
        $this->encryptor = $context->getEncryptor();
        parent::__construct($setup, $context, $cache, $attrGroupCollectionFactory);
    }


    /**
     * Default entities and attributes
     *
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getDefaultEntities()
    {
        return [
            'members_team' => [
                'entity_model' => 'Braem\Members\Model\ResourceModel\Team',
                'attribute_model' => 'Braem\Members\Model\ResourceModel\Eav\Attribute',
                'table' => 'braem_members_team',
                'additional_attribute_table' => 'braem_members_eav_attribute',
                'entity_attribute_collection' => 'Braem\Members\Model\ResourceModel\Team\Attribute\Collection',
                'attributes' => [
                    'name' => [
                        'type' => 'varchar',
                        'label' => 'Name',
                        'input' => 'text',
                        'sort_order' => 1,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
                    ],
                    'url_key' => [
                        'type' => 'varchar',
                        'label' => 'Url key',
                        'input' => 'text',
                        'sort_order' => 2,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
                        'required' => 0
                    ],
                ],
            ],
            'members_department' => [
                'entity_model' => 'Braem\Members\Model\ResourceModel\Department',
                'attribute_model' => 'Braem\Members\Model\ResourceModel\Eav\Attribute',
                'table' => 'braem_members_department',
                'additional_attribute_table' => 'braem_members_eav_attribute',
                'entity_attribute_collection' => 'Braem\Members\Model\ResourceModel\Department\Attribute\Collection',
                'attributes' => [
                    'name' => [
                        'type' => 'varchar',
                        'label' => 'Name',
                        'input' => 'text',
                        'sort_order' => 1,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
                    ],
                    'url_key' => [
                        'type' => 'varchar',
                        'label' => 'Url key',
                        'input' => 'text',
                        'sort_order' => 2,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
                        'required' => 0
                    ],
                ],
            ],
            'members_region' => [
                'entity_model' => 'Braem\Members\Model\ResourceModel\Region',
                'attribute_model' => 'Braem\Members\Model\ResourceModel\Eav\Attribute',
                'table' => 'braem_members_region',
                'additional_attribute_table' => 'braem_members_eav_attribute',
                'entity_attribute_collection' => 'Braem\Members\Model\ResourceModel\Region\Attribute\Collection',
                'attributes' => [
                    'name' => [
                        'type' => 'varchar',
                        'label' => 'Name',
                        'input' => 'text',
                        'sort_order' => 1,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
                    ],
                    'url_key' => [
                        'type' => 'varchar',
                        'label' => 'Url key',
                        'input' => 'text',
                        'sort_order' => 2,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
                        'required' => 0
                    ],
                ],
            ]
        ];
    }
}