<?php
namespace WeltPixel\DesignElements\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Catalog\Setup\CategorySetupFactory;

class AddCssAttributes implements DataPatchInterface, PatchVersionInterface
{

    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;


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
        $this->categorySetupFactory = $categorySetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $setup = $this->moduleDataSetup;
        $this->moduleDataSetup->startSetup();

        /** @var \Magento\Catalog\Setup\CategorySetup $categorySetup */
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);


        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'css_phone_small',
            [
                'group' => 'Design',
                'type' => 'text',
                'label' => 'Small Phone CSS',
                'input' => 'textarea',
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                'sort_order' => 0,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false
            ]
        );

        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'css_phone',
            [
                'group' => 'Design',
                'type' => 'text',
                'label' => 'Phone CSS',
                'input' => 'textarea',
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                'sort_order' => 0,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false
            ]
        );

        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'css_tablet_small',
            [
                'group' => 'Design',
                'type' => 'text',
                'label' => 'Small Tablet CSS',
                'input' => 'textarea',
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                'sort_order' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false
            ]
        );

        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'css_tablet',
            [
                'group' => 'Design',
                'type' => 'text',
                'label' => 'Tablet CSS',
                'input' => 'textarea',
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                'sort_order' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false
            ]
        );
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'css_desktop',
            [
                'group' => 'Design',
                'type' => 'text',
                'label' => 'Desktop CSS',
                'input' => 'textarea',
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                'sort_order' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false
            ]
        );
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'css_desktop_large',
            [
                'group' => 'Design',
                'type' => 'text',
                'label' => 'Large Desktop CSS',
                'input' => 'textarea',
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                'sort_order' => 3,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false
            ]
        );

        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'custom_js',
            [
                'group' => 'Design',
                'type' => 'text',
                'label' => 'Custom Js',
                'input' => 'textarea',
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                'sort_order' => 4,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false
            ]
        );

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.4.0';
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
