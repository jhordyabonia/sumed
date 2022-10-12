<?php
namespace WeltPixel\ProductLabels\Setup\Patch\Schema;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddLabelsValidityColumns implements SchemaPatchInterface, PatchVersionInterface
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

        $tableName = $installer->getTable('weltpixel_productlabels');
        if ($installer->getConnection()->isTableExists($tableName)) {
            $installer->getConnection()
                ->addColumn(
                    $tableName,
                    'valid_from',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                        'unsigned' => true,
                        'nullable' => true,
                        'comment'  => 'Valid From',
                        'default'  => NULL,
                        'after'    => 'status'
                    ]
                );

            $installer->getConnection()
                ->addColumn(
                    $tableName,
                    'valid_to',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                        'unsigned' => true,
                        'nullable' => true,
                        'comment'  => 'Valid To',
                        'default'  => NULL,
                        'after'    => 'valid_from'
                    ]
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
            AddLabelsIndexColumnsTables::class
        ];
    }
}
