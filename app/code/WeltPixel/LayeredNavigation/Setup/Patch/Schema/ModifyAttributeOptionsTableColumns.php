<?php
namespace WeltPixel\LayeredNavigation\Setup\Patch\Schema;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class ModifyAttributeOptionsTableColumns implements SchemaPatchInterface, PatchVersionInterface
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
        $setup = $this->schemaSetup;
        $setup->startSetup();
        $connection = $setup->getConnection();

        $tableName = $setup->getTable('weltpixel_ln_attribute_options');

        if ($setup->getConnection()->isTableExists($tableName)) {
            $columns = [
                'instant_search' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'default' => 0,
                    'unsigned' => true,
                    'nullable' => false,
                    'comment' => 'Instant Search Desktop',
                ],
                'instant_search_mobile' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'default' => 0,
                    'unsigned' => true,
                    'nullable' => false,
                    'comment' => 'Instant Search Mobile',
                ],
                'category_visibility' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'default' => 0,
                    'unsigned' => true,
                    'nullable' => false,
                    'comment' => 'Category Visibility',
                ],
                'category_ids' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'unsigned' => true,
                    'nullable' => true,
                    'comment' => 'Categories ids list',
                ],
                'keep_open_after_filter' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'default' => 0,
                    'unsigned' => true,
                    'nullable' => false,
                    'comment' => 'Keep attribute opened after filtering',
                    'after' => 'is_multiselect'
                ]
            ];

            foreach ($columns as $name => $definition) {
                if (!$connection->tableColumnExists($tableName, $name)) {
                    $connection->addColumn($tableName, $name, $definition);
                }
            }
        }

        $setup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.0.4';
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
            AddAttributeOptionsTable::class
        ];
    }
}
