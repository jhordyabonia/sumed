<?php
namespace WeltPixel\ProductLabels\Setup\Patch\Schema;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddLabelsTables implements SchemaPatchInterface, PatchVersionInterface
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
         * Create table 'weltpixel_productlabels'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('weltpixel_productlabels'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Title'
            )->addColumn(
                'priority',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Status'
            )->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Status'
            )->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Store id'
            )->addColumn(
                'customer_group',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Customer Group'
            )->addColumn(
                'conditions_serialized',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '64k',
                ['nullable' => true, 'default' => ''],
                'Conditions'
            )->addColumn(
                'product_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Product Position'
            )->addColumn(
                'product_image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Product Image'
            )->addColumn(
                'product_text',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => ''],
                'Product Image'
            )->addColumn(
                'product_text_bg_color',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                [],
                'Product Text Background Color'
            )->addColumn(
                'product_text_font_size',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                [],
                'Product Text Font Size'
            )->addColumn(
                'product_text_font_color',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                [],
                'Product Text Font Color'
            )->addColumn(
                'product_text_padding',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                [],
                'Product Text Padding'
            )->addColumn(
                'product_css',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '64k',
                [],
                'Product Css'
            )->addColumn(
                'category_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Category Position'
            )->addColumn(
                'category_image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Category Image'
            )->addColumn(
                'category_text',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => ''],
                'Category Image'
            )->addColumn(
                'category_text_bg_color',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                [],
                'Category Text Background Color'
            )->addColumn(
                'category_text_font_size',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                [],
                'Category Text Font Size'
            )->addColumn(
                'category_text_font_color',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                [],
                'Category Text Font Color'
            )->addColumn(
                'category_text_padding',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                [],
                'Category Text Padding'
            )->addColumn(
                'category_css',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '64k',
                [],
                'Category Css'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Created Time'
            )->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Update Time'
            )->addIndex(
                $this->schemaSetup->getIdxName(
                    $installer->getTable('weltpixel_productlabels'),
                    ['title','product_text','category_text'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['title','product_text','category_text'],
                ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
            )->setComment(
                'WeltPixel Product Labels'
            );

        $installer->getConnection()->createTable($table);

        $this->schemaSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.0.0';
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
