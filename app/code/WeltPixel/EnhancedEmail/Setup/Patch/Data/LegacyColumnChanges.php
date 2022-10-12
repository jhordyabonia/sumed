<?php
namespace WeltPixel\EnhancedEmail\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;


class LegacyColumnChanges implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;


    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ){
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $tableName = 'email_template';
        $isLegacyColumnExists = $this->moduleDataSetup->getConnection()->tableColumnExists($this->moduleDataSetup->getTable($tableName), 'is_legacy');
        if ($isLegacyColumnExists) {
            $this->moduleDataSetup
                ->getConnection()
                ->update($this->moduleDataSetup->getTable($tableName), ['is_legacy' => '1'], 'template_code like "%WeltPixel%"');
        }

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.0.10';
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
            EmailTemplateNewRefactorings::class
        ];
    }
}
