<?php
namespace WeltPixel\NavigationLinks\Setup\Patch\Data;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddCategoryBlockAttributes implements DataPatchInterface, PatchVersionInterface
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

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_top_block', [
            'type' => 'text',
            'label' => 'Top Custom HTML',
            'input' => 'text',
            'required' => false,
            'sort_order' => 4,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'wysiwyg_enabled' => true,
            'group' => 'WeltPixel Mega Menu Options',
        ]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_right_block', [
            'type' => 'text',
            'label' => 'Right Custom HTML',
            'input' => 'text',
            'required' => false,
            'sort_order' => 5,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'wysiwyg_enabled' => true,
            'group' => 'WeltPixel Mega Menu Options',
        ]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_bottom_block', [
            'type' => 'text',
            'label' => 'Bottom Custom HTML',
            'input' => 'text',
            'required' => false,
            'sort_order' => 6,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'wysiwyg_enabled' => true,
            'group' => 'WeltPixel Mega Menu Options',
        ]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_left_block', [
            'type' => 'text',
            'label' => 'Left Custom HTML',
            'input' => 'text',
            'required' => false,
            'sort_order' => 7,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'wysiwyg_enabled' => true,
            'group' => 'WeltPixel Mega Menu Options',
        ]);

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.2.1';
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
            AddCategoryDisplayAttributes::class
        ];
    }
}
