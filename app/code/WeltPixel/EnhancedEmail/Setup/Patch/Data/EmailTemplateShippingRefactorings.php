<?php
namespace WeltPixel\EnhancedEmail\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Email\Model\ResourceModel\Template\CollectionFactory;
use Magento\Email\Model\Template;


class EmailTemplateShippingRefactorings implements DataPatchInterface, PatchVersionInterface
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
     * @var Template
     */
    private $_template;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CollectionFactory $collectionFactory
     * @param Template $template
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CollectionFactory $collectionFactory,
        Template $template
    ){
        $this->moduleDataSetup = $moduleDataSetup;
        $this->_collectionFactory = $collectionFactory;
        $this->_template = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $collection = $this->_collectionFactory->create();
        foreach ($collection as $tpl) {
            $templateCode = $tpl->getOrigTemplateCode();
            if ($templateCode == 'new_shipment_weltpixel' || $templateCode == 'new_shipment_guest_weltpixel') {
                $newText = $templateText = $tpl->getTemplateText();
                $template = $this->_template->load($tpl->getTemplateId());

                $text = 'Magento_Sales::email/shipment/track.phtml';
                $modifText = 'WeltPixel_EnhancedEmail::email/shipment/track.phtml';
                $newText = str_replace($text, $modifText, $templateText);
                $template->setTemplateText($newText);
                $template->save();
            }
        }

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.0.8';
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
            EmailTemplateHeaderRefactorings::class
        ];
    }
}
