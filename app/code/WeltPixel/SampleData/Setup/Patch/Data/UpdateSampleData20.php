<?php
namespace WeltPixel\SampleData\Setup\Patch\Data;

use Magento\Framework\Setup;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use WeltPixel\SampleData\Setup\Updater;
use Magento\Cms\Model\BlockFactory;

class UpdateSampleData20 implements DataPatchInterface, PatchVersionInterface
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
     * @var \WeltPixel\SampleData\Model\Owl
     */
    protected $owl;

    /**
     * @var BlockFactory
     */
    protected $blockFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param Setup\SampleData\Executor $executor
     * @param Updater $updater
     * @param \Magento\Framework\App\State $state
     * @param \WeltPixel\SampleData\Model\Owl $owl
     * @param BlockFactory $blockFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        Setup\SampleData\Executor $executor,
        Updater $updater,
        \Magento\Framework\App\State $state,
        \WeltPixel\SampleData\Model\Owl $owl,
        BlockFactory $blockFactory
    ){
        $this->moduleDataSetup = $moduleDataSetup;
        $this->executor = $executor;
        $this->updater = $updater;
        $this->state = $state;
        $this->blockFactory = $blockFactory;
        $this->owl = $owl;
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

        /** Brands Widget Page */
        $blockIds = $this->getBlockIdsForBrandsWidgets();
        $sliderIds = $this->owl->update('1.1.20');
        $this->updater->setPagesToCreate('WeltPixel_SampleData::fixtures/pages/pages_1.1.20.csv', $sliderIds, $blockIds);
        $this->executor->exec($this->updater);

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.1.20';
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
            UpdateSampleData19::class
        ];
    }

    /**
     * @return array
     */
    protected function getBlockIdsForBrandsWidgets() {
        $blockIds = [];
        $block = $this->blockFactory->create();

        for ($i = 1; $i<=3; $i++) {
            $block->load('brands_sample_block' . $i, 'identifier');
            $blockIds[] = $block->getId();
        }

        return $blockIds;

    }
}
