<?php
namespace WeltPixel\Newsletter\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddNewsletterExitIntentBlock implements DataPatchInterface, PatchVersionInterface
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

        $exitIntentBlockData = [
            [
                'title' => 'WeltPixel Exit Intent Newsletter V1 Form',
                'identifier' => 'weltpixel_exitintent_newsletter_v1',
                'stores' => [0],
                'is_active' => 1,
                'content' => '
<!-- LEFT CONTENT SECTION BEGIN -->
<div class="wpn-col-md-7 weltpixel_newsletter_signup_section">
<div class="weltpixel_exitintent_newsletter_step_container">
<div class="title">ONE STEP CLOSER TO 20% OFF</div>

<!-- NEWSLETTER LOGIN BLOCK BEGIN -->
{{block class="Magento\Framework\View\Element\Template" name="weltpixel_exitintent_newsletter_popup" template="WeltPixel_Newsletter::popup_exitintent.phtml"}}
<!-- NEWSLETTER LOGIN BLOCK END -->

<!-- SOCIAL LOGIN WIDGET -->
<div class="sl-widget">{{widget type="WeltPixel\SocialLogin\Block\Widget\Login" type_name="Social Login Block"}}</div>
<!-- SOCIAL LOGIN WIDGET END -->

<p><strong>SIGN UP FOR NEW ARRIVALS AND INSIDER-ONLY DISCOUNTS</strong></p>
<!-- SOCIAL ICONS BEGIN -->
<div>
<a href="#" class="social-icons si-dark si-rounded si-facebook">
    <i class="icon-facebook"></i>
    <i class="icon-facebook"></i>
</a>

<a href="#" class="social-icons si-dark si-rounded si-twitter">
    <i class="icon-twitter"></i>
    <i class="icon-twitter"></i>
</a>

<a href="#" class="social-icons si-dark si-rounded si-instagram">
    <i class="icon-instagram"></i>
    <i class="icon-instagram"></i>
</a>

<a href="#" class="social-icons si-dark si-rounded si-vimeo">
    <i class="icon-vimeo"></i>
    <i class="icon-vimeo"></i>
</a>

<a href="#" class="social-icons si-dark si-rounded si-pinterest">
    <i class="icon-pinterest"></i>
    <i class="icon-pinterest"></i>
</a>
</div>
<!-- SOCIAL ICONS END -->
</div>
</div>
<!-- LEFT CONTENT SECTION END-->

<!-- IMAGE SECTION BEGIN -->
<div class="wpn-col-md-5 col-last">
<img class="image-fade" alt="Newsletter Fashion Box" src="{{view url=\'WeltPixel_Newsletter/images/popup_image.jpg\'}}">
</div>
<!-- IMAGE SECTION END -->'
            ],
            [
                'title' => 'WeltPixel Exit Intent Newsletter V1 Step 1',
                'identifier' => 'weltpixel_exitintent_newsletter_v1_step_1',
                'stores' => [0],
                'is_active' => 1,
                'content' => '
<div class="title">LEAVING SO SOON?</div>
<p>Get 20% off your first purchase by signing up for our newsletter!</p>
'
            ],
            [
                'title' => 'WeltPixel Exit Intent Newsletter V2 Form',
                'identifier' => 'weltpixel_exitintent_newsletter_v2',
                'stores' => [0],
                'is_active' => 1,
                'content' => '
                    <!-- IMAGE SECTION BEGIN -->
<div class="left-section">
<img class="image-fade" alt="Newsletter Fashion Box" src="{{view url=\'WeltPixel_Newsletter/images/popup_image.jpg\'}}" >
</div>
<!-- IMAGE SECTION END -->

<div class="weltpixel_newsletter_signup_section">
<div class="weltpixel_exitintent_newsletter_step_container">

<div class="middle-section">
<div class="title">ONE STEP CLOSER TO 20% OFF</div>
<p><strong>SIGN UP FOR NEW ARRIVALS AND INSIDER-ONLY DISCOUNTS</strong></p>
</div>

<div class="right-section">
<!-- NEWSLETTER LOGIN BLOCK BEGIN -->
{{block class="Magento\Framework\View\Element\Template" name="weltpixel_exitintent_newsletter_popup" template="WeltPixel_Newsletter::popup_exitintent.phtml"}}
<!-- NEWSLETTER LOGIN BLOCK END -->

<!-- SOCIAL LOGIN WIDGET -->
<div class="sl-widget">{{widget type="WeltPixel\SocialLogin\Block\Widget\Login" type_name="Social Login Block"}}</div>
<!-- SOCIAL LOGIN WIDGET END -->

</div>
</div>
</div>'
            ],
            [
                'title' => 'WeltPixel Exit Intent Newsletter V2 Step 1',
                'identifier' => 'weltpixel_exitintent_newsletter_v2_step_1',
                'stores' => [0],
                'is_active' => 1,
                'content' => '
<div class="middle-section">
<div class="title">LEAVING SO SOON?</div>
<p>Get 20% off your first purchase by signing up for our newsletter!</p>
</div>'
            ],
            [
                'title' => 'WeltPixel Exit Intent Newsletter V3 Form',
                'identifier' => 'weltpixel_exitintent_newsletter_v3',
                'stores' => [0],
                'is_active' => 1,
                'content' => '<!-- BACKGROUND IMAGE -->
<img class="image-background" alt="Newsletter Fashion Box" src="{{view url=\'WeltPixel_Newsletter/images/popup_image.jpg\'}}">
<!-- BACKGROUND IMAGE -->

<div class="weltpixel_newsletter_signup_section">
<div class="weltpixel_exitintent_newsletter_step_container">
<div class="title">GET 20% OFF!</div>
<p><strong>SIGN UP FOR NEW ARRIVALS AND INSIDER-ONLY DISCOUNTS</strong></p>

<!-- NEWSLETTER LOGIN BLOCK BEGIN -->
<div class="newsletter-signup">
{{block class="Magento\Framework\View\Element\Template" name="weltpixel_exitintent_newsletter_popup" template="WeltPixel_Newsletter::popup_exitintent.phtml"}}
</div>
<!-- NEWSLETTER LOGIN BLOCK END -->

<!-- SOCIAL LOGIN WIDGET -->
<div class="sl-widget">{{widget type="WeltPixel\SocialLogin\Block\Widget\Login" type_name="Social Login Block"}}</div>
<!-- SOCIAL LOGIN WIDGET END -->

</div>
</div>'
            ],
            [
                'title' => 'WeltPixel Exit Intent Newsletter V3 Step 1',
                'identifier' => 'weltpixel_exitintent_newsletter_v3_step_1',
                'stores' => [0],
                'is_active' => 1,
                'content' => '<div class="title">DON\'T GO YET!</div>
<p>GET $20 OFF ON YOUR FIRST PURCHASE ;)</p>'
            ],
            [
                'title' => 'WeltPixel Exit Intent Newsletter V4 Form',
                'identifier' => 'weltpixel_exitintent_newsletter_v4',
                'stores' => [0],
                'is_active' => 1,
                'content' => '<div class="weltpixel_newsletter_signup_section">
<div class="weltpixel_exitintent_newsletter_step_container">
<div class="title">ONE STEP CLOSER TO 20% OFF</div>
<p><strong>SIGN UP FOR NEW ARRIVALS AND INSIDER-ONLY DISCOUNTS</strong></p>

<!-- NEWSLETTER LOGIN BLOCK BEGIN -->
<div class="newsletter-signup">
{{block class="Magento\Framework\View\Element\Template" name="weltpixel_exitintent_newsletter_popup" template="WeltPixel_Newsletter::popup_exitintent.phtml"}}
</div>
<!-- NEWSLETTER LOGIN BLOCK END -->

<!-- SOCIAL LOGIN WIDGET -->
<div class="sl-widget">{{widget type="WeltPixel\SocialLogin\Block\Widget\Login" type_name="Social Login Block"}}</div>
<!-- SOCIAL LOGIN WIDGET END -->

</div>
</div>'
            ],
            [
                'title' => 'WeltPixel Exit Intent Newsletter V4 Step 1',
                'identifier' => 'weltpixel_exitintent_newsletter_v4_step_1',
                'stores' => [0],
                'is_active' => 1,
                'content' => '<div class="title">Want To Save 10%</div>'
            ]
        ];

        foreach ($exitIntentBlockData as $newsletterBlockData) {
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
        return '1.0.5';
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
            AddNewsletterV4Block::class
        ];
    }
}
