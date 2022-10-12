<?php
namespace WeltPixel\SampleData\Setup\Patch\Data;

use Magento\Framework\Setup;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use WeltPixel\SampleData\Setup\Updater;
use Magento\Cms\Model\BlockFactory;

class UpdateSampleData10 implements DataPatchInterface, PatchVersionInterface
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
     * @var BlockFactory
     */
    protected $blockFactory;

    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param Setup\SampleData\Executor $executor
     * @param Updater $updater
     * @param \Magento\Framework\App\State $state
     * @param BlockFactory $blockFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        Setup\SampleData\Executor $executor,
        Updater $updater,
        \Magento\Framework\App\State $state,
        BlockFactory $blockFactory
    ){
        $this->moduleDataSetup = $moduleDataSetup;
        $this->executor = $executor;
        $this->updater = $updater;
        $this->state = $state;
        $this->blockFactory = $blockFactory;
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

        $block = $this->blockFactory->create();
        $blockCollection = $block->getCollection()
            ->addFieldToFilter('identifier', array(
                array("like" => 'weltpixel_footer_%'),
                array("like" => 'weltpixel_pre-footer')
            ));

        if ($blockCollection->getSize()) {
            foreach ($blockCollection->getItems() as $item) {
                $item->setData('is_active', 1);
                try {
                    $item->save();
                } catch (\Exception $ex) {}
            }
        }

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.1.10';
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
            UpdateSampleData9::class
        ];
    }
}
