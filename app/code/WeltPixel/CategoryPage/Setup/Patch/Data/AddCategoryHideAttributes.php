<?php
namespace WeltPixel\CategoryPage\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Setup\CategorySetupFactory;

class AddCategoryHideAttributes implements DataPatchInterface, PatchVersionInterface
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
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CategorySetupFactory $categorySetupFactory
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->catalogSetupFactory = $categorySetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $setup = $this->moduleDataSetup;
        $this->moduleDataSetup->startSetup();

        /** @var \Magento\Catalog\Setup\CategorySetup $catalogSetup */
        $catalogSetup = $this->catalogSetupFactory->create(['setup' => $setup]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_hide_title', [
            'type' => 'int',
            'label' => 'Hide Page Title',
            'input' => 'select',
            'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'required' => false,
            'sort_order' => 3,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'WeltPixel Options'
        ]);

        $catalogSetup->addAttribute(Category::ENTITY, 'weltpixel_hide_breadcrumbs', [
            'type' => 'int',
            'label' => 'Hide Breadcrumbs',
            'input' => 'select',
            'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'required' => false,
            'sort_order' => 4,
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
        return '1.0.0';
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
