<?php
namespace WeltPixel\TitleRewrite\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;

class AddTitleRewriteAttribute implements DataPatchInterface, PatchVersionInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @param EavSetupFactory $eavSetupFactory
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        ModuleDataSetupInterface $moduleDataSetup
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleManager = $moduleManager;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        /**
         * Forced verification of the Backend module
         */
        if (!$this->moduleManager->isEnabled('WeltPixel_Backend')) {
            throw new \Magento\Framework\Validator\Exception(__('WeltPixel_Backend module must be enabled.'));
        }


        $setup = $this->moduleDataSetup;
        $this->moduleDataSetup->startSetup();

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /**
         * Add attributes to the eav/attribute
         */
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'title_rewrite',
            [
                'type' => 'varchar',
                'label' => 'Category Name Rewrite',
                'input' => 'text',
                'required' => false,
                'sort_order' => 40,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => false,
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'title_rewrite',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Product Name Rewrite',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Product Details',
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'sort_order' => 10,
            ]
        );

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
