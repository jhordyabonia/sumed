<?php
namespace WeltPixel\ProductPage\Setup\Patch\Schema;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddViewValuesTable implements SchemaPatchInterface, PatchVersionInterface
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

        $table = $installer->getConnection()
            ->newTable( $installer->getTable( 'weltpixel_product_view_values' ) )
            ->addColumn(
                'entity_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                [ 'identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true ],
                'Entity Id'
            )
            ->addColumn(
                'version_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                11,
                [ 'nullable' => false ],
                'Version Id'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                11,
                [ 'nullable' => false ],
                'Store Id'
            )
            ->addColumn(
                'values',
                \Magento\Framework\Db\Ddl\Table::TYPE_TEXT,
                '2M',
                [],
                'Values'
            );
        $installer->getConnection()->createTable( $table );

        $this->schemaSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.1.2';
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
