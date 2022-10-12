<?php
namespace WeltPixel\DesignElements\Setup\Patch\Schema;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddCssColumns implements SchemaPatchInterface, PatchVersionInterface
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
        $this->schemaSetup->startSetup();

        $setup->getConnection()->addColumn(
            $setup->getTable('cms_page'),
            'css_phone_small',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '64k',
                'nullable' => true,
                'comment' => 'Custom CSS - Small Phone'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('cms_page'),
            'css_phone',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '64k',
                'nullable' => true,
                'comment' => 'Custom CSS - Phone'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('cms_page'),
            'css_tablet_small',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '64k',
                'nullable' => true,
                'comment' => 'Custom CSS - Small Tablet'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('cms_page'),
            'css_tablet',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '64k',
                'nullable' => true,
                'comment' => 'Custom CSS - Tablet'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('cms_page'),
            'css_desktop',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '64k',
                'nullable' => true,
                'comment' => 'Custom CSS - Desktop Medium'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('cms_page'),
            'css_desktop_large',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '64k',
                'nullable' => true,
                'comment' => 'Custom CSS - Desktop Large'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('cms_page'),
            'custom_js',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '64k',
                'nullable' => true,
                'comment' => 'Custom Js'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('cms_block'),
            'css_phone_small',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '64k',
                'nullable' => true,
                'comment' => 'Custom CSS - Small Phone'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('cms_block'),
            'css_phone',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '64k',
                'nullable' => true,
                'comment' => 'Custom CSS - Phone'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('cms_block'),
            'css_tablet_small',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '64k',
                'nullable' => true,
                'comment' => 'Custom CSS - Small Tablet'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('cms_block'),
            'css_tablet',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '64k',
                'nullable' => true,
                'comment' => 'Custom CSS - Tablet'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('cms_block'),
            'css_desktop',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '64k',
                'nullable' => true,
                'comment' => 'Custom CSS - Desktop Medium'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('cms_block'),
            'css_desktop_large',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '64k',
                'nullable' => true,
                'comment' => 'Custom CSS - Desktop Large'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('cms_block'),
            'custom_js',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '64k',
                'nullable' => true,
                'comment' => 'Custom Js'
            ]
        );

        $this->schemaSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.4.0';
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
