<?php
namespace WeltPixel\ProductPage\Setup\Patch\Schema;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddVisitorCounterTable implements SchemaPatchInterface, PatchVersionInterface
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
            ->newTable( $installer->getTable( 'weltpixel_product_visitor_counter' ) )
            ->addColumn(
                'entity_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                [ 'identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true ],
                'Entity Id'
            )
            ->addColumn(
                'session_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_TEXT,
                64,
                [ 'nullable' => false ],
                'Session Id'
            )
            ->addColumn(
                'product_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                11,
                [ 'nullable' => false ],
                'Product Id'
            )
            ->addColumn(
                'last_visit_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Update Time'
            )->addIndex(
                $installer->getIdxName(
                    $installer->getTable('weltpixel_product_visitor_counter'),
                    ['session_id', 'product_id']
                ),
                ['session_id', 'product_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            );
        $installer->getConnection()->createTable( $table );

        $this->schemaSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.1.4';
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
            AddViewValuesTable::class
        ];
    }
}
