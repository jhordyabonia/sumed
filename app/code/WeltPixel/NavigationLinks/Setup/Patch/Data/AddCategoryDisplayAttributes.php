<?php
namespace WeltPixel\NavigationLinks\Setup\Patch\Data;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddCategoryDisplayAttributes implements DataPatchInterface, PatchVersionInterface
{
    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $catalogSetupFactory;

    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CategorySetupFactory $catalogSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CategorySetupFactory $catalogSetupFactory)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->catalogSetupFactory = $catalogSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $setup = $this->moduleDataSetup;

        /** @var \Magento\Catalog\Setup\CategorySetup $categorySetup */
        $catalogSetup = $this->catalogSetupFactory->create(['setup' => $setup]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_display_mode', [
            'type' => 'varchar',
            'label' => 'Display Mode',
            'input' => 'select',
            'default' => 'sectioned',
            'required' => false,
            'sort_order' => 1,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'WeltPixel Mega Menu Options'
        ]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_columns_number', [
            'type' => 'text',
            'label' => 'Number of columns in dropdown menu',
            'input' => 'text',
            'default' => '4',
            'required' => false,
            'sort_order' => 2,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'wysiwyg_enabled' => false,
            'group' => 'WeltPixel Mega Menu Options',
        ]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_column_width', [
            'type' => 'text',
            'label' => 'Column Width',
            'input' => 'text',
            'default' => 'auto',
            'required' => false,
            'sort_order' => 3,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'wysiwyg_enabled' => false,
            'group' => 'WeltPixel Mega Menu Options',
        ]);

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.2.0';
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
            AddCategoryUrlNewTabAttribute::class
        ];
    }
}
