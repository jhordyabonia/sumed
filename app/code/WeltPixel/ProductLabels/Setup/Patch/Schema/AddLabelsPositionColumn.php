<?php
namespace WeltPixel\ProductLabels\Setup\Patch\Schema;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddLabelsPositionColumn implements SchemaPatchInterface, PatchVersionInterface
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
                    'product_page_position',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        'unsigned' => true,
                        'nullable' => false,
                        'default' => '1',
                        'after' => 'conditions_serialized',
                        'comment' => 'Position in Product Page'
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
        return '1.0.5';
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
            AddLabelsValidityColumns::class
        ];
    }
}
