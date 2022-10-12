<?php
namespace WeltPixel\ProductLabels\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use WeltPixel\ProductLabels\Model\ProductLabelsFactory;
use WeltPixel\ProductLabels\Model\ResourceModel\ProductLabels\CollectionFactory;
use Magento\Framework\App\State;

class AddSampleData implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var ProductLabelsFactory
     */
    private $productLabelsFactory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var State
     */
    private $appState;

    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ProductLabelsFactory $productLabelsFactory
     * @param CollectionFactory $collectionFactory
     * @param State $appState
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ProductLabelsFactory $productLabelsFactory,
        CollectionFactory $collectionFactory,
        State $appState
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->productLabelsFactory = $productLabelsFactory;
        $this->collectionFactory = $collectionFactory;
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

        $collection = $this->collectionFactory->create();

        if($collection->getSize() < 1) {
            $sampleData = [
                [
                    'title' => 'NEW',
                    'priority' => '0',
                    'status' => '0',
                    'store_id' => '0',
                    'customer_group' => '0,1,2,3',
                    'product_position' => '3',
                    'product_text' => 'NEW',
                    'product_text_bg_color' => '#000000',
                    'product_text_font_size' => '12px',
                    'product_text_font_color' => '#FFFFFF',
                    'product_text_padding' => '8px 15px',
                    'product_css' => 'border-radius:30px; margin-top: 12px;',
                    'category_position' => '3',
                    'category_text' => 'NEW',
                    'category_text_bg_color' => '#000000',
                    'category_text_font_size' => '11px',
                    'category_text_font_color' => '#FFFFFF',
                    'category_text_padding' => '8px 15px 8px 15px',
                    'category_css' => 'border-radius:30px; ',
                ],
                [
                    'title' => 'Sale',
                    'priority' => '0',
                    'status' => '0',
                    'store_id' => '0',
                    'customer_group' => '0,1,2,3',
                    'product_position' => '1',
                    'product_text' => 'SALE',
                    'product_text_bg_color' => '#D83701',
                    'product_text_font_size' => '11px',
                    'product_text_font_color' => '#FFFFFF',
                    'product_text_padding' => '8px 15px 8px 15px',
                    'product_css' => '',
                    'category_position' => '1',
                    'category_text' => 'SALE',
                    'category_text_bg_color' => '#D83701',
                    'category_text_font_size' => '11px',
                    'category_text_font_color' => '#FFFFFF',
                    'category_text_padding' => '8px 15px 8px 15px',
                    'category_css' => '',
                ],
            ];

            foreach ($sampleData as $data) {
                $productLabel = $this->productLabelsFactory->create();
                $productLabel->setData($data);
                try {
                    $productLabel->save();
                } catch (\Exception $ex) {}
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
            \WeltPixel\ProductLabels\Setup\Patch\Schema\AddLabelsValidityColumns::class
        ];
    }
}
