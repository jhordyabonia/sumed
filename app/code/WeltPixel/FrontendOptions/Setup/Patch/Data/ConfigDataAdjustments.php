<?php
namespace WeltPixel\FrontendOptions\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Framework\App\State;

class ConfigDataAdjustments implements DataPatchInterface, PatchVersionInterface
{

    /**
     * @var State
     */
    private $appState;

    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;


    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param BlockFactory $blockFactory
     * @param State $appState
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        State $appState
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->appState = $appState;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        try {
            if(!$this->appState->isAreaCodeEmulated()) {
                $this->appState->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);
            }
        } catch (\Exception $ex) {}

        $setup = $this->moduleDataSetup;
        $setup->startSetup();
        $connection = $setup->getConnection();
        $configDataTable = $setup->getTable('core_config_data');

        $connection->delete($configDataTable, '`path` LIKE "%weltpixel_frontend_options/paragraph%"');

        $setup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.1.1';
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
