<?php
namespace WeltPixel\EnhancedEmail\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Email\Model\TemplateFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\State;

class AddEmailConfigurations implements DataPatchInterface, PatchVersionInterface
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
     * @var TemplateFactory
     */
    private $templateFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var State
     */
    private $appState;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param WriterInterface $configWriter
     * @param TemplateFactory $templateFactory
     * @param StoreManagerInterface $storeManager
     * @param State $appState
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        WriterInterface $configWriter,
        TemplateFactory $templateFactory,
        StoreManagerInterface $storeManager,
        State $appState)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->configWriter = $configWriter;
        $this->templateFactory = $templateFactory;
        $this->storeManager = $storeManager;
        $this->appState = $appState;
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
        } catch (\Exception $ex) {}


        $orderMarkup = 'layout handle="order_markup" order=$order area="frontend"';
        $invoiceMarkup = 'layout handle="invoice_markup" invoice=$invoice area="frontend"';
        $creditmemoMarkup = 'layout handle="creditmemo_markup" creditmemo=$creditmemo area="frontend"';
        $customerAction = 'layout handle="customer_action" customer=$customer area="frontend"';
        $customerAccountAction = 'layout handle="customer_account_action" customer=$customer area="frontend"';
        $customerAccountConfirmAction = 'layout handle="customer_account_confirm_action" customer=$customer back_url=$back_url area="frontend"';
        $customerAccountNoPasswordAction = 'layout handle="customer_account_no_password_action" customer=$customer back_url=$back_url area="frontend"';
        $subscriptionConfirmationAction = 'layout handle="subscription_confirmation_action" area="frontend"';
        $forgotAdminPasswordAction = 'layout handle="forgot_admin_password_action" area="frontend"';
        $wishlistSharingAction = 'layout handle="wishlist_sharing_action" area="frontend"';

        $this->configWriter->save('weltpixel/enhancedemail/order_markup', $orderMarkup, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
        $this->configWriter->save('weltpixel/enhancedemail/invoice_markup', $invoiceMarkup, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
        $this->configWriter->save('weltpixel/enhancedemail/creditmemo_markup', $creditmemoMarkup, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
        $this->configWriter->save('weltpixel/enhancedemail/customer_action', $customerAction, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
        $this->configWriter->save('weltpixel/enhancedemail/customer_account_action', $customerAccountAction, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
        $this->configWriter->save('weltpixel/enhancedemail/customer_account_confirm_action', $customerAccountConfirmAction, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
        $this->configWriter->save('weltpixel/enhancedemail/customer_account_no_password_action', $customerAccountNoPasswordAction, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
        $this->configWriter->save('weltpixel/enhancedemail/subscription_confirmation_action', $subscriptionConfirmationAction, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
        $this->configWriter->save('weltpixel/enhancedemail/forgot_admin_password_action', $forgotAdminPasswordAction, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
        $this->configWriter->save('weltpixel/enhancedemail/wishlist_sharing_action', $wishlistSharingAction, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);

        $this->configWriter->save('weltpixel_enhancedemail/general/logo_link', $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB), $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);


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
