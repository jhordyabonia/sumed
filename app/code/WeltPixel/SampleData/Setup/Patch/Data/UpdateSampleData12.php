<?php
namespace WeltPixel\SampleData\Setup\Patch\Data;

use Magento\Framework\Setup;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use WeltPixel\SampleData\Setup\Updater;

class UpdateSampleData12 implements DataPatchInterface, PatchVersionInterface
{

    /**
     * @var Setup\SampleData\Executor
     */
    private $executor;

    /**
     * @var Updater
     */
    private $updater;

    /**
     * @var \Magento\Framework\App\State
     */
    private $state;

    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param Setup\SampleData\Executor $executor
     * @param Updater $updater
     * @param \Magento\Framework\App\State $state
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        Setup\SampleData\Executor $executor,
        Updater $updater,
        \Magento\Framework\App\State $state
    ){
        $this->moduleDataSetup = $moduleDataSetup;
        $this->executor = $executor;
        $this->updater = $updater;
        $this->state = $state;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        try {
            if(!$this->state->isAreaCodeEmulated()) {
                $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);
            }
        } catch (\Exception $ex) {}

        /** Global message promo block */
        $this->updater->setBlocksToCreate('WeltPixel_SampleData::fixtures/blocks/blocks_1.1.12.csv');
        $this->executor->exec($this->updater);

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.1.12';
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
            UpdateSampleData11::class
        ];
    }
}
