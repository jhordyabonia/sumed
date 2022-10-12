<?php
namespace WeltPixel\NavigationLinks\Setup\Patch\Data;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddCategorySubcategoryAttributes implements DataPatchInterface, PatchVersionInterface
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

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_sc_layout', [
            'type' => 'varchar',
            'label' => 'Category Layout',
            'input' => 'select',
            'default' => 'default',
            'required' => false,
            'sort_order' => 10,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'WeltPixel Options'
        ]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_sc_columns', [
            'type' => 'int',
            'label' => 'Number of Columns',
            'input' => 'select',
            'default' => '4',
            'required' => false,
            'sort_order' => 11,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'WeltPixel Options'
        ]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_sc_title_position', [
            'type' => 'varchar',
            'label' => 'Sub-category title position',
            'input' => 'select',
            'default' => 'under_image',
            'required' => false,
            'sort_order' => 12,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'WeltPixel Options'
        ]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_sc_image', [
            'type' => 'varchar',
            'label' => 'Subcategory Image',
            'input' => 'image',
            'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
            'required' => false,
            'sort_order' => 12,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'WeltPixel Options'
        ]);

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.2.9';
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
            AddCategoryDynamicScAttributes::class
        ];
    }
}
