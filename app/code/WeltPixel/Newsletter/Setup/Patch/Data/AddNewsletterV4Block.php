<?php
namespace WeltPixel\Newsletter\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddNewsletterV4Block implements DataPatchInterface, PatchVersionInterface
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

        $newsletterV4BlockData = [
            [
                'title' => 'WeltPixel Newsletter V4 Form',
                'identifier' => 'weltpixel_newsletter_v4',
                'stores' => [0],
                'is_active' => 1,
                'content' => '<div class="weltpixel_newsletter_signup_section">
<div class="weltpixel_newsletter_step_container">
<div class="title">10% Off Your First Order</div>
<p>Join our mailing list</p>

<!-- NEWSLETTER LOGIN BLOCK BEGIN -->
<div class="newsletter-signup">
{{block class="Magento\Framework\View\Element\Template" name="weltpixel_newsletter_popup" template="WeltPixel_Newsletter::popup.phtml"}}
</div>
<!-- NEWSLETTER LOGIN BLOCK END -->

<!-- SOCIAL LOGIN WIDGET -->
<div class="sl-widget">{{widget type="WeltPixel\SocialLogin\Block\Widget\Login" type_name="Social Login Block"}}</div>
<!-- SOCIAL LOGIN WIDGET END -->

</div>
</div>'
            ],
            [
                'title' => 'WeltPixel Newsletter V4 Step 1',
                'identifier' => 'weltpixel_newsletter_v4_step_1',
                'stores' => [0],
                'is_active' => 1,
                'content' => '<div class="title">Want To Save 10%</div>'
            ]
        ];

        foreach ($newsletterV4BlockData as $newsletterBlockData) {
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
        return '1.0.4';
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
            AddNewsletterV3Block::class
        ];
    }
}
