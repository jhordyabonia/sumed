<?php
namespace WeltPixel\EnhancedEmail\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Email\Model\ResourceModel\Template\CollectionFactory;
use Magento\Email\Model\Template;


class EmailTemplateHeaderRefactorings implements DataPatchInterface, PatchVersionInterface
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
            $newText = $templateText = $tpl->getTemplateText();
            $text = '{{layout handle="preheader_section" area="frontend"}}';
            $template = $this->_template->load($tpl->getTemplateId());
            $newText = str_replace($text, '', $templateText);
            if ($templateCode != 'design_email_header_template' || $templateCode != 'design_email_footer_template') {
                $searchStr = '{{template config_path="design/email/header_template"}}';
                $pos = strpos($newText, $searchStr);
                if ($pos !== false) {
                    $newText = substr_replace($newText, $text, $pos + strlen($searchStr), 0);
                }
            }

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
        return '1.0.7';
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
            EmailTemplateRefactorings::class
        ];
    }
}
