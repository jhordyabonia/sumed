<?php
namespace WeltPixel\EnhancedEmail\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Cms\Model\BlockFactory;

class AddCmsBlockData implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @var WriterInterface
     */
    private $configWriter;

    /**
     * @var BlockFactory
     */
    private $blockFactory;


    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param BlockFactory $blockFactory
     * @param WriterInterface $configWriter
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        BlockFactory $blockFactory,
        WriterInterface $configWriter
    ){
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockFactory = $blockFactory;
        $this->configWriter = $configWriter;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $content = <<<EOT
                <a style="padding-left: 30px" href="http://facebook.com/#"><img src="{{view url='WeltPixel_EnhancedEmail/images/fb.png'}}" alt="fb" width="15" height="15" /></a>
                <a style="padding-left: 30px" href="http://twitter.com/#"><img src="{{view url='WeltPixel_EnhancedEmail/images/twitter.png'}}" alt="twitter" width="15" height="15" /></a>
                <a style="padding-left: 30px" href="http://instagram.com/#"><img src="{{view url='WeltPixel_EnhancedEmail/images/instagram.png'}}" alt="instagram" width="15" height="15" /></a>
                <a style="padding-left: 30px" href="http://youtube.com/#"><img src="{{view url='WeltPixel_EnhancedEmail/images/youtube.png'}}" alt="youtube" width="15" height="15" /></a>
EOT;

        // social media block
        $cmsBlockData = [
            'title' => 'EnhancedEmail Social Media Block',
            'identifier' => 'weltpixel_social_media_email_block',
            'content' => $content,
            'is_active' => 1,
            'stores' => [0],
            'sort_order' => 0
        ];

        try {
            $this->blockFactory->create()->setData($cmsBlockData)->save();
        } catch (\Exception $ex) {
        }

        // custom block
        $cmsCustomBlockData = [
            'title' => 'EnhancedEmail Custom Block',
            'identifier' => 'weltpixel_custom_block_1',
            'content' => "<h3>Enhanced Email custom block content.</h3>",
            'is_active' => 1,
            'stores' => [0],
            'sort_order' => 0
        ];

        try {
            $this->blockFactory->create()->setData($cmsCustomBlockData)->save();
        } catch (\Exception $ex) {
        }

        $socialMediaBlock = <<<EOT
block class="Magento\\\Cms\\\Block\\\Block" area="frontend" block_id="weltpixel_social_media_email_block"
EOT;
        $customBlock = <<<EOT
block class="Magento\\\Cms\\\Block\\\Block" area="frontend" block_id="weltpixel_custom_block_1"
EOT;
        $this->configWriter->save('weltpixel/enhancedemail/social_media_email_block', $socialMediaBlock, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
        $this->configWriter->save('weltpixel/enhancedemail/custom_block_1', $customBlock, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);


        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.0.1';
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
            AddEmailConfigurations::class
        ];
    }
}
