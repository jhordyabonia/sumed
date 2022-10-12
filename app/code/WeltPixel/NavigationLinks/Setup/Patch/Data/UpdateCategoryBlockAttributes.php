<?php
namespace WeltPixel\NavigationLinks\Setup\Patch\Data;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class UpdateCategoryBlockAttributes implements DataPatchInterface, PatchVersionInterface
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

        $attributes = [
            'weltpixel_mm_top_block' => [
                'is_wysiwyg_enabled' => false,
                'is_html_allowed_on_front' => true,
            ],
            'weltpixel_mm_right_block' => [
                'is_wysiwyg_enabled' => false,
                'is_html_allowed_on_front' => true,
            ],
            'weltpixel_mm_bottom_block' => [
                'is_wysiwyg_enabled' => false,
                'is_html_allowed_on_front' => true,
            ],
            'weltpixel_mm_left_block' => [
                'is_wysiwyg_enabled' => false,
                'is_html_allowed_on_front' => true,
            ]
        ];

        foreach ($attributes as $attrCode => $fieldValue) {
            foreach ($fieldValue as $field => $value) {
                $catalogSetup->updateAttribute(Category::ENTITY, $attrCode, $field, $value);
            }
        }

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.2.5';
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
            UpdateCategoryUrlAttribute::class
        ];
    }
}
