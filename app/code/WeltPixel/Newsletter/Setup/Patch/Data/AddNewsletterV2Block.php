<?php
namespace WeltPixel\Newsletter\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddNewsletterV2Block implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $blockFactory;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     * @param \Magento\Framework\App\State $state
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Magento\Framework\App\State $state)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockFactory = $blockFactory;
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

        $newsletterV2BlockData = [
            [
                'title' => 'WeltPixel Newsletter V2 Form',
                'identifier' => 'weltpixel_newsletter_v2',
                'stores' => [0],
                'is_active' => 1,
                'content' => '
                    <!-- IMAGE SECTION BEGIN -->
<div class="left-section">
<img class="image-fade" alt="Newsletter Fashion Box" src="{{view url=\'WeltPixel_Newsletter/images/popup_image.jpg\'}}" >
</div>
<!-- IMAGE SECTION END -->

<div class="weltpixel_newsletter_signup_section">
<div class="weltpixel_newsletter_step_container">

<div class="middle-section">
<div class="title">JOIN OUR MAILING LIST</div>
<p><strong>SIGN UP FOR NEW ARRIVALS AND INSIDER-ONLY DISCOUNTS</strong></p>
</div>

<div class="right-section">
<!-- NEWSLETTER LOGIN BLOCK BEGIN -->
{{block class="Magento\Framework\View\Element\Template" name="weltpixel_newsletter_popup" template="WeltPixel_Newsletter::popup.phtml"}}
<!-- NEWSLETTER LOGIN BLOCK END -->

<!-- SOCIAL LOGIN WIDGET -->
<div class="sl-widget">{{widget type="WeltPixel\SocialLogin\Block\Widget\Login" type_name="Social Login Block"}}</div>
<!-- SOCIAL LOGIN WIDGET END -->

</div>
</div>
</div>'
            ],
            [
                'title' => 'WeltPixel Newsletter V2 Step 1',
                'identifier' => 'weltpixel_newsletter_v2_step_1',
                'stores' => [0],
                'is_active' => 1,
                'content' => '
<div class="middle-section">
<div class="title">GET 10% DISCOUNT OFF</div>
<p><strong>SIGN UP FOR NEW ARRIVALS AND INSIDER-ONLY DISCOUNTS</strong></p>
</div>'
            ]
        ];

        foreach ($newsletterV2BlockData as $newsletterBlockData) {
            $blockModel = $this->blockFactory->create()->setData($newsletterBlockData);
            try {
                $blockModel->save();
            } catch (\Exception $ex) {
            }
        }

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.0.2';
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
            AddNewsletterV1Block::class
        ];
    }
}
