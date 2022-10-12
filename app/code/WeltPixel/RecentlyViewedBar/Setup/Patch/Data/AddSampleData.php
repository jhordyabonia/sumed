<?php
namespace WeltPixel\RecentlyViewedBar\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\State;
use Magento\Cms\Model\BlockFactory;

class AddSampleData implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var State
     */
    private $appState;
    /**
     * @var BlockFactory
     */
    private $blockFactory;

    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param StoreManagerInterface $storeManager
     * @param State $appState
     * @param BlockFactory $blockFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        StoreManagerInterface $storeManager,
        State $appState,
        BlockFactory $blockFactory
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->storeManager = $storeManager;
        $this->appState = $appState;
        $this->blockFactory = $blockFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        try {
            if(!$this->appState->isAreaCodeEmulated()) {
                $this->appState->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);
            }
        } catch (\Exception $ex) {
        }

        $content = <<<EOT
<div class="arv-cms-img"><img class="arv-desktop-img" src="{{view url='WeltPixel_RecentlyViewedBar/images/desktop_sample.png'}}"> <img class="arv-mobile-img" src="{{view url='WeltPixel_RecentlyViewedBar/images/mobile_sample.png'}}"></div>
EOT;

        $cmsBlockData = [
            'title' => 'Recently Viewed Bar - Sample Block',
            'identifier' => 'rvb_sample_block',
            'content' => $content,
            'is_active' => 1,
            'stores' => [0],
            'sort_order' => 0
        ];

        try {
            $this->blockFactory->create()->setData($cmsBlockData)->save();
        } catch (\Exception $ex) {
        }


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
