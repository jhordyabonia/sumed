<?php
namespace WeltPixel\OwlCarouselSlider\Setup\Patch\Schema;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class ModifySliderTableColumnPro implements SchemaPatchInterface, PatchVersionInterface
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
        $bannersTableName = $setup->getTable('weltpixel_owlcarouselslider_banners');
        $slidersTableName = $setup->getTable('weltpixel_owlcarouselslider_sliders');
        if (!$connection->tableColumnExists($bannersTableName, 'mobile_image')) {
            $connection->addColumn(
                $bannersTableName,
                'mobile_image',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '255',
                    'nullable' => true,
                    'default' => '',
                    'comment' => 'Mobile Image',
                    'after' => 'image'
                ]
            );
        }

        if (!$connection->tableColumnExists($bannersTableName, 'wrap_link')) {
            $connection->addColumn(
                $bannersTableName,
                'wrap_link',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '255',
                    'nullable' => true,
                    'default' => '0',
                    'comment' => 'Wrap Link',
                    'after' => 'url'
                ]
            );
        }

        $setup->getConnection()->modifyColumn(
            $bannersTableName,
            'slider_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
            ]
        );

        $setup->getConnection()->modifyColumn(
            $bannersTableName,
            'sort_order',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
            ]
        );

        if (!$connection->tableColumnExists($bannersTableName, 'thumb_image')) {
            $connection->addColumn(
                $bannersTableName,
                'thumb_image',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'after' => 'mobile_image',
                    'comment' => 'Thumb Image'
                ]
            );
        }

        if (!$connection->tableColumnExists($slidersTableName, 'thumbs')) {
            $connection->addColumn(
                $slidersTableName,
                'thumbs',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'nullable' => true,
                    'unsigned' => true,
                    'default' => 0,
                    'comment' => 'thumbs',
                    'after' => 'dots'
                ]
            );
        }

        if (!$connection->tableColumnExists($slidersTableName, 'dotsEach')) {
            $connection->addColumn(
                $slidersTableName,
                'dotsEach',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'nullable' => true,
                    'unsigned' => true,
                    'default' => 0,
                    'comment' => 'dotsEach',
                    'after' => 'dots'
                ]
            );
        }

        if (!$connection->tableColumnExists($slidersTableName, 'navSpeed')) {
            $connection->addColumn(
                $slidersTableName,
                'navSpeed',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => true,
                    'unsigned' => true,
                    'default' => 0,
                    'comment' => 'navSpeed',
                    'after' => 'autoHeight'
                ]
            );
        }

        if (!$connection->tableColumnExists($slidersTableName, 'dotsSpeed')) {
            $connection->addColumn(
                $slidersTableName,
                'dotsSpeed',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => true,
                    'unsigned' => true,
                    'default' => 0,
                    'comment' => 'dotsSpeed',
                    'after' => 'autoHeight'
                ]
            );
        }

        if (!$connection->tableColumnExists($slidersTableName, 'rtl')) {
            $connection->addColumn(
                $slidersTableName,
                'rtl',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'nullable' => true,
                    'unsigned' => true,
                    'default' => 0,
                    'comment' => 'rtl',
                    'after' => 'autoHeight'
                ]
            );
        }

        if (!$connection->tableColumnExists($slidersTableName, 'nav_border_radius')) {
            $connection->addColumn(
                $slidersTableName,
                'nav_border_radius',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'after' => 'navSpeed',
                    'comment' => 'Prev/Next button border radius'
                ]
            );
        }


        $setup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.1.7';
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
            ModifySliderTableColumn::class
        ];
    }
}
