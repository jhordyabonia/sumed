<?php
namespace WeltPixel\SmartProductTabs\Setup\Patch\Schema;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddTabsIndexTable implements SchemaPatchInterface, PatchVersionInterface
{
    /**
     * @var SchemaSetupInterface $schemaSetup
     */
    private $schemaSetup;

    /**
     * @param SchemaSetupInterface $schemaSetup
     */
    public function __construct(SchemaSetupInterface $schemaSetup)
    {
        $this->schemaSetup = $schemaSetup;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $installer = $this->schemaSetup;
        $this->schemaSetup->startSetup();


        /**
         * Create table 'weltpixel_smartproducttabs_rule_idx'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('weltpixel_smartproducttabs_rule_idx')
        )
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'rule_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Rule Id'
            )->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Product Id'
            )->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Store id'
            )->addIndex(
                $installer->getIdxName(
                    $installer->getTable('weltpixel_smartproducttabs_rule_idx'),
                    ['rule_id']
                ),
                ['rule_id']
            )->addIndex(
                $installer->getIdxName(
                    $installer->getTable('weltpixel_smartproducttabs_rule_idx'),
                    ['product_id']
                ),
                ['product_id']
            )->addIndex(
                $installer->getIdxName(
                    $installer->getTable('weltpixel_smartproducttabs_rule_idx'),
                    ['store_id']
                ),
                ['store_id']
            )->setComment(
                'WeltPixel Smart Product Tabs Rules Index'
            );

        $installer->getConnection()->createTable($table);

        $this->schemaSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.0.2';
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [
            AddTabsTable::class
        ];
    }
}
