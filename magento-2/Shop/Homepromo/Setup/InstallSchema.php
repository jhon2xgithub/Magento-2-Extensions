<?php
namespace Shop\Homepromo\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (!$installer->tableExists('shop_homepromo')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('shop_homepromo'))
                ->addColumn(
                    'promo_id',
                    Table::TYPE_INTEGER,
                    10,
                    ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true]
                )
                ->addColumn('promo_name', Table::TYPE_TEXT, 255, ['nullable' => false])               
                ->addColumn('creation_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Creation Time')
                ->addColumn('update_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Update Time')
                ->setComment('Sample table');

            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('topdesventes')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('topdesventes'))
                ->addColumn('promo_id', Table::TYPE_INTEGER, 10, ['nullable' => false, 'unsigned' => true])
                ->addColumn('product_id', Table::TYPE_INTEGER, 10, ['nullable' => false, 'unsigned' => true], 'Magento Product Id')
                ->addForeignKey(
                    $installer->getFkName(
                        'shop_homepromo',
                        'promo_id',
                        'topdesventes',
                        'promo_id'
                    ),
                    'promo_id',
                    $installer->getTable('shop_homepromo'),
                    'promo_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $installer->getFkName(
                        'topdesventes',
                        'promo_id',
                        'catalog_product_entity',
                        'entity_id'
                    ),
                    'product_id',
                    $installer->getTable('catalog_product_entity'),
                    'entity_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Promo Product Attachment relation table');

            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('ideescadeaux')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('ideescadeaux'))
                ->addColumn('promo_id', Table::TYPE_INTEGER, 10, ['nullable' => false, 'unsigned' => true])
                ->addColumn('product_id', Table::TYPE_INTEGER, 10, ['nullable' => false, 'unsigned' => true], 'Magento Product Id')
                ->addForeignKey(
                    $installer->getFkName(
                        'shop_homepromo',
                        'promo_id',
                        'ideescadeaux',
                        'promo_id'
                    ),
                    'promo_id',
                    $installer->getTable('shop_homepromo'),
                    'promo_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $installer->getFkName(
                        'ideescadeaux',
                        'promo_id',
                        'catalog_product_entity',
                        'entity_id'
                    ),
                    'product_id',
                    $installer->getTable('catalog_product_entity'),
                    'entity_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Promo Product Attachment relation table');
             

            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('nauveautes')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('nauveautes'))
                ->addColumn('promo_id', Table::TYPE_INTEGER, 10, ['nullable' => false, 'unsigned' => true])
                ->addColumn('product_id', Table::TYPE_INTEGER, 10, ['nullable' => false, 'unsigned' => true], 'Magento Product Id')
                ->addForeignKey(
                    $installer->getFkName(
                        'shop_homepromo',
                        'promo_id',
                        'nauveautes',
                        'promo_id'
                    ),
                    'promo_id',
                    $installer->getTable('shop_homepromo'),
                    'promo_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(    
                    $installer->getFkName(
                        'nauveautes',
                        'promo_id',
                        'catalog_product_entity',
                        'entity_id'
                    ),
                    'product_id',
                    $installer->getTable('catalog_product_entity'),
                    'entity_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Promo Product Attachment relation table');
                

            $installer->getConnection()->createTable($table);
        }



        $installer->endSetup();
    }
}
