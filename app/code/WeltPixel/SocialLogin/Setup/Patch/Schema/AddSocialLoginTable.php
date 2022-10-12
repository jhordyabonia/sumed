<?php
namespace WeltPixel\SocialLogin\Setup\Patch\Schema;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddSocialLoginTable implements SchemaPatchInterface, PatchVersionInterface
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
        $installer = $this->schemaSetup;

        if (!$installer->tableExists('weltpixel_sociallogin_customer')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('weltpixel_sociallogin_customer'))
                ->addColumn('id', Table::TYPE_INTEGER, 11, [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true,
                ], 'SocialLogin User Id')
                ->addColumn('sociallogin_id', Table::TYPE_TEXT, 255, ['unsigned' => true, 'nullable => false'], 'SocialLogin Id')
                ->addColumn('customer_id', Table::TYPE_INTEGER, 10, ['unsigned' => true, 'nullable => false'], 'Magento Customer Id')
                ->addColumn('type', Table::TYPE_TEXT, 255, ['default' => ''], 'Type')
                ->addColumn('created_at', \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT], 'Created At')
                ->addForeignKey(
                    $installer->getFkName('weltpixel_sociallogin_customer', 'customer_id', 'customer_entity', 'entity_id'),
                    'customer_id',
                    $installer->getTable('customer_entity'),
                    'entity_id',
                    Table::ACTION_CASCADE)
                ->setComment('SocialLogin Customer Table');

            $installer->getConnection()->createTable($table);
        }


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
