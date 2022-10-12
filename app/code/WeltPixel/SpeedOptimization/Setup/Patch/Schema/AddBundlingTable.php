<?php
namespace WeltPixel\SpeedOptimization\Setup\Patch\Schema;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddBundlingTable implements SchemaPatchInterface, PatchVersionInterface
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
         * Create table 'weltpixel_speedoptimization_jsbundling'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('weltpixel_speedoptimization_jsbundling'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'themepath',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                64,
                ['nullable' => false, 'default' => ''],
                'Theme Path'
            )->addColumn(
                'bundling_identifier',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                64,
                ['nullable' => false, 'default' => ''],
                'Bundling Identifier'
            )->addColumn(
                'requiredfields',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '64k',
                ['nullable' => false, 'default' => ''],
                'Bundling Fields'
            )->addIndex(
                $installer->getIdxName(
                    $installer->getTable('weltpixel_speedoptimization_jsbundling'),
                    ['themepath']
                ),
                ['themepath']
            )->addIndex(
                $installer->getIdxName(
                    $installer->getTable('weltpixel_speedoptimization_jsbundling'),
                    ['bundling_identifier']
                ),
                ['bundling_identifier']
            )->setComment(
                'WeltPixel SpeedOptimization JsBundling'
            );

        $installer->getConnection()->createTable($table);

        $this->schemaSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.0.1';
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
        return [];
    }
}
