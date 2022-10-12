<?php
namespace WeltPixel\NavigationLinks\Setup\Patch\Data;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddCategoryBlockNewAttributes implements DataPatchInterface, PatchVersionInterface
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

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_top_block_type', [
            'type' => 'varchar',
            'label' => 'Top Block',
            'input' => 'select',
            'default' => 'none',
            'required' => false,
            'sort_order' => 8,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'WeltPixel Mega Menu Options'
        ]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_top_block_cms', [
            'type' => 'varchar',
            'label' => 'Top CMS Block',
            'input' => 'select',
            'required' => false,
            'sort_order' => 9,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'WeltPixel Mega Menu Options'
        ]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_right_block_type', [
            'type' => 'varchar',
            'label' => 'Right Block',
            'input' => 'select',
            'default' => 'none',
            'required' => false,
            'sort_order' => 10,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'WeltPixel Mega Menu Options'
        ]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_right_block_cms', [
            'type' => 'varchar',
            'label' => 'Right CMS Block',
            'input' => 'select',
            'required' => false,
            'sort_order' => 11,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'WeltPixel Mega Menu Options'
        ]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_bottom_block_type', [
            'type' => 'varchar',
            'label' => 'Bottom Block',
            'input' => 'select',
            'default' => 'none',
            'required' => false,
            'sort_order' => 12,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'WeltPixel Mega Menu Options'
        ]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_bottom_block_cms', [
            'type' => 'varchar',
            'label' => 'Bottom CMS Block',
            'input' => 'select',
            'required' => false,
            'sort_order' => 13,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'WeltPixel Mega Menu Options'
        ]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_left_block_type', [
            'type' => 'varchar',
            'label' => 'Left Block',
            'input' => 'select',
            'default' => 'none',
            'required' => false,
            'sort_order' => 14,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'WeltPixel Mega Menu Options'
        ]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_left_block_cms', [
            'type' => 'varchar',
            'label' => 'Left CMS Block',
            'input' => 'select',
            'required' => false,
            'sort_order' => 15,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'WeltPixel Mega Menu Options'
        ]);

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.2.2';
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
            AddCategoryBlockAttributes::class
        ];
    }
}
