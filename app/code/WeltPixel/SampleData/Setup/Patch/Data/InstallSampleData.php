<?php
namespace WeltPixel\SampleData\Setup\Patch\Data;

use Magento\Framework\Setup;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use WeltPixel\SampleData\Setup\Installer;

class InstallSampleData implements DataPatchInterface, PatchVersionInterface
{

    /**
     * @var Setup\SampleData\Executor
     */
    private $executor;

    /**
     * @var Installer
     */
    private $installer;

    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param Setup\SampleData\Executor $executor
     * @param Installer $installer
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        Setup\SampleData\Executor $executor,
        Installer $installer
    ){
        $this->moduleDataSetup = $moduleDataSetup;
        $this->executor = $executor;
        $this->installer = $installer;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $this->executor->exec($this->installer);

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
