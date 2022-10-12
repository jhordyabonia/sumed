<?php
namespace WeltPixel\NavigationLinks\Setup\Patch\Data;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddCategoryImageAttributes implements DataPatchInterface, PatchVersionInterface
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

        $catalogSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'weltpixel_mm_image', [
            'type' => 'varchar',
            'label' => 'Menu Image',
            'input' => 'image',
            'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
            'required' => false,
            'sort_order' => 20,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'WeltPixel Mega Menu Options'
        ]);
        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_image_width', [
            'type' => 'int',
            'label' => 'Menu Image Width',
            'input' => 'text',
            'required' => false,
            'sort_order' => 21,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'wysiwyg_enabled' => false,
            'group' => 'WeltPixel Mega Menu Options',
        ]);
        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_image_height', [
            'type' => 'int',
            'label' => 'Menu Image Height',
            'input' => 'text',
            'required' => false,
            'sort_order' => 22,
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
        return '1.2.11';
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
            AddCategorySubcategoryHideAttribute::class
        ];
    }
}
