<?php
namespace WeltPixel\EnhancedEmail\Setup\Patch\Data;

use Magento\Framework\App\TemplateTypesInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Email\Model\TemplateFactory;
use Magento\Email\Model\ResourceModel\Template\CollectionFactory;
use Magento\Framework\App\ProductMetadataInterface;


class AddEmailForgotPasswordTemplate implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @var CollectionFactory
     */
    private $_collectionFactory;

    /**
     * @var TemplateFactory
     */
    private $templateFactory;

    /**
     * @var ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * @var array|array[]
     */
    private $_templates = [];

    private $magentoVersion;


    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CollectionFactory $collectionFactory
     * @param TemplateFactory $templateFactory
     * @pqram ProductMetadataInterface $productMetadata
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CollectionFactory $collectionFactory,
        TemplateFactory $templateFactory,
        ProductMetadataInterface $productMetadata
    ){
        $this->moduleDataSetup = $moduleDataSetup;
        $this->_collectionFactory = $collectionFactory;
        $this->templateFactory = $templateFactory;
        $this->productMetadata = $productMetadata;
        $this->_templates = $this->_getTemplatesArr();
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $this->magentoVersion = $this->productMetadata->getVersion();
        $templateType = TemplateTypesInterface::TYPE_HTML;

        $tplCode = 'Forgot Password - WeltPixel';
        if (!$this->_templateExist($tplCode)) {
            $tplPreheader = 'Forgot your account password?';
            $tplVars = <<<'EOT'
{"var this.getUrl(store, 'customer/account/')":"Customer Account URL","var customer.name":"Customer Name"}
EOT;
            $tplText = <<<'EOT'
{{layout handle="preheader_section" area="frontend"}}
{{template config_path="design/email/header_template"}}
{{layout handle="customer_action" customer=$customer area="frontend"}}

<table align="center" style="background-color:#000; text-align:center; width: 660px">
    <tbody>
    <tr>
        <td class="dark"  style="padding-bottom:8px; padding-top:5px; background-color:#000">
            <h3 style="text-align: center; text-transform: uppercase;">
                {{trans "SHHH ..."}}
            </h3>
        </td>
    </tr>
    <tr>
        <td class="dark" align="center" style="padding-bottom:0px; background-color:#000">
            <h1 style="text-align: center; margin: 0 !important">
                {{trans 'Passwords are hard to remember!' }}
            </h1>
        </td>
    </tr>
    <tr>
        <td class="dark" align="center" style="padding-bottom:8px; background-color:#000">
            <h3 style="text-align: center;">
                {{trans 'Totally get it.'}}
            </h3>
        </td>
    </tr>
    </tbody>
</table>

<p class="greeting" style="margin-top: 35px">{{trans "Hello,"}}</p>
<br>
<p style="margin: 20px 0 !important"> {{trans "If you requested this change, set a new password here:"}}</p>

<table class="button" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td>
            <table class="inner-wrapper" border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
                <tr>
                    <td align="center" style="padding: 8px 0 !important">
                        <a href="{{var this.getUrl($store,'customer/account/createPassword',[_query:[id:$customer.id,token:$customer.rp_token],_nosid:1])}}" target="_blank" style="font-weight: bold">{{trans "SET IT!"}}</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<p>{{trans "If you did not make this request, you can ignore this email and your password will remain the same."}}</p>

{{template config_path="design/email/footer_template"}}
EOT;
            $tplSubject = $this->_getEmailForgotpasswordTplSubject();
            $template = $this->templateFactory->create();
            $template->setTemplateCode($tplCode);
            $template->setTemplateText($tplText);
            $template->setTemplatePreheader($tplPreheader);
            $template->setOrigTemplateVariables($tplVars);
            $template->setTemplateType($templateType);
            $template->setTemplateStyles('');
            $template->setTemplateSubject($tplSubject);
            $template->setOrigTemplateCode('customer_password_reset_confirmation_weltpixel');
            $template->save();
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
            AddEmailTemplates::class
        ];
    }

    /**
     * @return array
     */
    protected function _getTemplatesArr()
    {
        $collection = $this->_collectionFactory->create();
        $templates = [];
        foreach ($collection as $template) {
            $templates[] = [
                'template_code' => $template->getTemplateCode(),
                'orig_template_code' => $template->getOrigTemplateCode()
            ];
        }

        return $templates;
    }

    /**
     * @param $templateCode
     * @return false|int|string
     */
    protected function _templateExist($templateCode)
    {
        return array_search($templateCode, array_column($this->_templates, 'template_code'));
    }

    /**
     * @return string
     */
    protected function _getEmailForgotpasswordTplSubject() {
        $tplSubject = <<<'EOT'
{{trans "Reset your %store_name password" store_name=$store.frontend_name}}
EOT;
        if ($this->isLegacy()) {
            $tplSubject = <<<'EOT'
{{trans "Reset your %store_name password" store_name=$store.getFrontendName()}}
EOT;
        }
        return $tplSubject;
    }


    /**
     * @return bool
     */
    public function isLegacy() {
        if (version_compare($this->magentoVersion, '2.3.4', '<')) {
            return true;
        }

        return false;
    }
}
