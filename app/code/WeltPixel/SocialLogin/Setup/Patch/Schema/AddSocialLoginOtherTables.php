<?php
namespace WeltPixel\SocialLogin\Setup\Patch\Schema;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddSocialLoginOtherTables implements SchemaPatchInterface, PatchVersionInterface
{
    /**
     * @var SchemaSetupInterface $schemaSetup
     */
    private $schemaSetup;

    /**
     * @param SchemaSetupInterface $schemaSetup
     */
    public function __construct(
        SchemaSetupInterface $schemaSetup
    )
    {
        $this->schemaSetup = $schemaSetup;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->schemaSetup->startSetup();
        $setup = $this->schemaSetup;

        if (!$setup->tableExists('weltpixel_sociallogin_order_user')) {
            $table = $setup->getConnection()
                ->newTable($setup->getTable('weltpixel_sociallogin_order_user'))
                ->addColumn('id', Table::TYPE_INTEGER, 11, [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true,
                ], 'Id')
                ->addColumn('order_id', Table::TYPE_INTEGER, 10, ['unsigned' => true, 'nullable => false'], 'Magento Order Id')
                ->addColumn('user_id', Table::TYPE_INTEGER, 10, ['unsigned' => true, 'nullable => false'], 'SocialLogin User Id')
                ->addColumn('customer_id', Table::TYPE_INTEGER, 10, ['unsigned' => true, 'nullable => false'], 'Magento Customer Id')
                ->addColumn('type', Table::TYPE_TEXT, 255, ['default' => ''], 'Type')
                ->addForeignKey(
                    $setup->getFkName('weltpixel_sociallogin_order_user', 'order_id', 'sales_order', 'entity_id'),
                    'order_id',
                    $setup->getTable('sales_order'),
                    'entity_id',
                    Table::ACTION_CASCADE)
                ->setComment('SocialLogin Order User Link Table');

            $setup->getConnection()->createTable($table);
        }

        if (!$setup->tableExists('weltpixel_sociallogin_analytics')) {
            $table = $setup->getConnection()
                ->newTable($setup->getTable('weltpixel_sociallogin_analytics'))
                ->addColumn('id', Table::TYPE_INTEGER, 11, [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true,
                ], 'Id')
                ->addColumn('type', Table::TYPE_TEXT, 255, ['default' => ''], 'Type')
                ->addColumn('type_data', Table::TYPE_TEXT, Table::MAX_TEXT_SIZE, ['default' => ''], 'Type Data Object encoded')
                ->setComment('SocialLogin Analytics Table');

            $setup->getConnection()->createTable($table);
        }

        if ($setup->getConnection()->tableColumnExists($setup->getTable('weltpixel_sociallogin_analytics'), 'created_at') === false) {
            $setup->getConnection()->addColumn(
                $setup->getTable('weltpixel_sociallogin_analytics'),
                'created_at',
                [
                    'type' => Table::TYPE_TIMESTAMP,
                    'size' => null,
                    'nullable' => false,
                    'default' => Table::TIMESTAMP_INIT,
                    'comment' => 'Created Date'
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
            AddSocialLoginTable::class
        ];
    }
}
