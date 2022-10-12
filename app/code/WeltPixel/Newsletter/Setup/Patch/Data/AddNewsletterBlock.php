<?php
namespace WeltPixel\Newsletter\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class AddNewsletterBlock implements DataPatchInterface, PatchVersionInterface
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

        $newsletterBlockData = [
            'title' => 'WelPixel Newsletter',
            'identifier' => 'weltpixel_newsletter',
            'stores' => [0],
            'is_active' => 1,
            'content' => '
<!-- LEFT CONTENT SECTION BEGIN -->
<div class="wpn-col-md-7 weltpixel_newsletter_signup_section">
<div class="title">JOIN OUR MAILING LIST</div>

<p>Pellentesque de fermentum mollis comodous an loremous</p>

<!-- NEWSLETTER LOGIN BLOCK BEGIN -->
{{block class="Magento\Framework\View\Element\Template" name="weltpixel_newsletter_popup" template="WeltPixel_Newsletter::popup.phtml"}}
<!-- NEWSLETTER LOGIN BLOCK END -->

<p><strong>SIGN UP FOR EXCLUSIVE UPDATES, NEW ARRIVALS AND INSIDER-ONLY DISCOUNTS</strong></p>
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
<!-- LEFT CONTENT SECTION END-->

<!-- IMAGE SECTION BEGIN -->
<div class="wpn-col-md-5 col-last" style="padding:0px;">
<img class="image-fade" alt="Newsletter Fashion Box" src="{{view url=\'WeltPixel_Newsletter/images/popup_image.jpg\'}}" style="width:100%">
</div>
<!-- IMAGE SECTION END -->'
        ];

        $blockModel = $this->blockFactory->create()->setData($newsletterBlockData);
        try {
            $blockModel->save();
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
