<?php
namespace WeltPixel\NavigationLinks\Setup\Patch\Data;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddCategoryLabelAttributes implements DataPatchInterface, PatchVersionInterface
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

        $catalogSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'weltpixel_mm_label_text', [
            'type' => 'varchar',
            'label' => 'Mega Menu Label Text',
            'input' => 'text',
            'required' => false,
            'sort_order' => 25,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'WeltPixel Mega Menu Options'
        ]);
        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_label_font_color', [
            'type' => 'text',
            'label' => 'Mega Menu Label Font Color',
            'input' => 'text',
            'default' => '',
            'required' => false,
            'sort_order' => 26,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'wysiwyg_enabled' => false,
            'group' => 'WeltPixel Mega Menu Options',
        ]);
        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_label_background_color', [
            'type' => 'text',
            'label' => 'Mega Menu Label Background Color',
            'input' => 'text',
            'default' => '',
            'required' => false,
            'sort_order' => 27,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'wysiwyg_enabled' => false,
            'group' => 'WeltPixel Mega Menu Options',
        ]);
        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_label_position', [
            'type' => 'varchar',
            'label' => 'Mega Menu Label Position',
            'input' => 'select',
            'default' => 'center',
            'required' => false,
            'sort_order' => 28,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'WeltPixel Mega Menu Options'
        ]);
        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_mm_image_alt', [
            'type' => 'text',
            'label' => 'Mega Menu Image Alt',
            'input' => 'text',
            'required' => false,
            'sort_order' => 29,
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
        return '1.2.14';
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
            AddCategoryImageNameAlignAttribute::class
        ];
    }
}
