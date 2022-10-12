<?php
namespace WeltPixel\NavigationLinks\Setup\Patch\Data;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class UpdateCategoryUrlAttribute implements DataPatchInterface, PatchVersionInterface
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

        $catalogSetup->updateAttribute(Category::ENTITY, 'weltpixel_category_url', 'note','', null);

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.2.4';
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
            AddCategoryHideAllcatAttribute::class
        ];
    }
}
