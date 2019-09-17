<?php

namespace Tychons\Page\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '0.0.1', '<')) {
            $installer->getConnection()->addColumn(
                $installer->getTable('tychons_page_post'),
                'test',
                [
                    'type' => Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length' => '12,4',
                    'comment' => 'test',
                    'after' => 'status'
                ]
            );
        }


        $installer->endSetup();
    }
}
