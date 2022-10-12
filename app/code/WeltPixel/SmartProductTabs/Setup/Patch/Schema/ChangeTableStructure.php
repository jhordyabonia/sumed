<?php
namespace WeltPixel\SmartProductTabs\Setup\Patch\Schema;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class ChangeTableStructure implements SchemaPatchInterface, PatchVersionInterface
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

        $tableName = $installer->getTable('weltpixel_smartproducttabs');
        if ($installer->getConnection()->isTableExists($tableName)) {
            $installer->getConnection()
                ->addColumn(
                    $tableName,
                    'name',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'nullable' => true,
                        'comment'  => 'Name',
                        'default'  => '',
                        'after'    => 'id'
                    ]
                );

            $installer->getConnection()->dropIndex(
                $installer->getTable('weltpixel_smartproducttabs'),
                $installer->getIdxName(
                    $installer->getTable('weltpixel_smartproducttabs'),
                    ['title','content'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                )
            );

            $installer->getConnection()->addIndex(
                $installer->getTable('weltpixel_smartproducttabs'),
                $installer->getIdxName(
                    $installer->getTable('weltpixel_smartproducttabs'),
                    ['name', 'title','content'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['name','title','content'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }

        $this->schemaSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.0.3';
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
            AddTabsIndexTable::class
        ];
    }
}
