<?php
namespace WeltPixel\EnhancedEmail\Setup\Patch\Data;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Email\Model\ResourceModel\Template\CollectionFactory;
use Magento\Email\Model\Template;

class MissingItemsFix implements DataPatchInterface, PatchVersionInterface
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
     * @var CollectionFactory
     */
    private $_collectionFactory;


    /**
     * @var Template
     */
    private $_template;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param WriterInterface $configWriter
     * @param CollectionFactory $collectionFactory
     * @param Template $template
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        WriterInterface $configWriter,
        CollectionFactory $collectionFactory,
        Template $template
    ){
        $this->moduleDataSetup = $moduleDataSetup;
        $this->configWriter = $configWriter;
        $this->_collectionFactory = $collectionFactory;
        $this->_template = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $orderMarkup = 'layout handle="order_markup" order=$order order_id=$order_id area="frontend"';
        $invoiceMarkup = 'layout handle="invoice_markup" invoice=$invoice invoice_id=$invoice_id area="frontend"';
        $creditmemoMarkup = 'layout handle="creditmemo_markup" creditmemo=$creditmemo creditmemo_id=$creditmemo_id area="frontend"';
        $shipmentMarkup = 'layout handle="shipment_markup" order=$order shipment=$shipment order=$order_id shipment_id=$shipment_id area="frontend"';
        $this->configWriter->save('weltpixel/enhancedemail/shipment_markup', $shipmentMarkup, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
        $this->configWriter->save('weltpixel/enhancedemail/order_markup', $orderMarkup, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
        $this->configWriter->save('weltpixel/enhancedemail/invoice_markup', $invoiceMarkup, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
        $this->configWriter->save('weltpixel/enhancedemail/creditmemo_markup', $creditmemoMarkup, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);

        $emailCollection = $this->_collectionFactory->create();
        foreach ($emailCollection as $tpl) {
            $newText = $tpl->getTemplateText();
            $template = $this->_template->load($tpl->getTemplateId());
            $newText = str_replace([
                '{{layout handle="order_markup" order=$order area="frontend"}}',
                '{{layout handle="invoice_markup" invoice=$invoice area="frontend"}}',
                '{{layout handle="creditmemo_markup" creditmemo=$creditmemo area="frontend"}}',
                '{{layout handle="weltpixel_sales_email_order_items" order=$order area="frontend"}}',
                '{{layout handle="weltpixel_sales_email_order_items" order=$order}}',
                '{{layout handle="weltpixel_sales_email_order_invoice_items" invoice=$invoice order=$order area="frontend"}}',
                '{{layout handle="weltpixel_sales_email_order_invoice_items" invoice=$invoice order=$order}}',
                '{{layout handle="weltpixel_sales_email_order_shipment_items" shipment=$shipment order=$order}}',
                '{{layout handle="weltpixel_sales_email_order_creditmemo_items" creditmemo=$creditmemo order=$order area="frontend"}}',
                '{{layout handle="weltpixel_sales_email_order_creditmemo_items" creditmemo=$creditmemo order=$order}}'
            ],[
                '{{layout handle="order_markup" order=$order order_id=$order_id area="frontend"}}',
                '{{layout handle="invoice_markup" invoice=$invoice invoice_id=$invoice_id area="frontend"}}',
                '{{layout handle="creditmemo_markup" creditmemo=$creditmemo creditmemo_id=$creditmemo_id area="frontend"}}',
                '{{layout handle="weltpixel_sales_email_order_items" order=$order order_id=$order_id area="frontend"}}',
                '{{layout handle="weltpixel_sales_email_order_items" order=$order order_id=$order_id}}',
                '{{layout handle="weltpixel_sales_email_order_invoice_items" invoice=$invoice order=$order invoice_id=$invoice_id order_id=$order_id area="frontend"}}',
                '{{layout handle="weltpixel_sales_email_order_invoice_items" invoice=$invoice order=$order invoice_id=$invoice_id order_id=$order_id}}',
                '{{layout handle="weltpixel_sales_email_order_shipment_items" shipment=$shipment order=$order shipment_id=$shipment_id order_id=$order_id}}',
                '{{layout handle="weltpixel_sales_email_order_creditmemo_items" creditmemo=$creditmemo order=$order creditmemo_id=$creditmemo_id order_id=$order_id area="frontend"}}',
                '{{layout handle="weltpixel_sales_email_order_creditmemo_items" creditmemo=$creditmemo order=$order creditmemo_id=$creditmemo_id order_id=$order_id}}'
            ], $newText);
            $template->setTemplateText($newText);
            $template->save();
        }

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.0.11';
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
            LegacyColumnChanges::class
        ];
    }
}
