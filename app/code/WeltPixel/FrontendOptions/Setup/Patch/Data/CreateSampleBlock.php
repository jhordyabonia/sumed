<?php
namespace WeltPixel\FrontendOptions\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Cms\Model\BlockFactory;
use Magento\Framework\App\State;
use WeltPixel\SampleData\Model\Block;

class CreateSampleBlock implements DataPatchInterface, PatchVersionInterface
{

    /**
     * @var State
     */
    private $appState;

    /**
     * @var Block
     */
    private $blockFactory;

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
        BlockFactory $blockFactory,
        State $appState
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockFactory = $blockFactory;
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
        $content = '<h1 class="contact-details">Contact details</h1>
                        <div class="container-wpx-contact">
                        <div class="col-md-4"><i class="icon-phone icon-4x"></i>
                        <div class="details">
                        <h4>Phone</h4>
                        <a href="tel:123-456-7890" class="gray-phone">PHONE:123-456-7890</a>
                        </div>
                        </div>
                        <div class="col-md-4 border-contact"><i class="icon-location icon-4x"></i>
                        <div class="details">
                        <h4>Adress</h4>
                        <p>12 Washington Avenue.<br />New York, USA</p>
                        </div>
                        </div>
                        <div class="col-md-4"><i class="icon-email3 icon-4x"></i>
                        <div class="details">
                        <h4>Email</h4>
                        <a href="mailto:contact@example.com" class="gray-phone">contact@example.com</a>
                        </div>
                        </div>
                        </div>
                        <h1 class="title-contact text-center">Get in touch with us</h1>
                        <p class="text-center mob-pd">Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur,</p>
                        <p class="text-center mob-pd">vel illum qui dolorem eum fugiat quo voluptas nulla pariatur erit qui inea</p>';
        $cmsBlockData = [
            'title' => 'Pearl Contact CMS block',
            'identifier' => 'pearl_contact_cms_block',
            'content' => $content,
            'is_active' => 1,
            'stores' => [0],
            'sort_order' => 0
        ];

        try {
            $this->blockFactory->create()->setData($cmsBlockData)->save();
        } catch (\Exception $ex) {
        }

        $setup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.1.2';
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
            ConfigDataAdjustments::class
        ];
    }
}
