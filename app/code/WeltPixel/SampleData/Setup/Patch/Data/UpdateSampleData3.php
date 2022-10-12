<?php
namespace WeltPixel\SampleData\Setup\Patch\Data;

use Magento\Framework\Setup;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Cms\Model\PageFactory;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;
use WeltPixel\SampleData\Setup\Updater;

class UpdateSampleData3 implements DataPatchInterface, PatchVersionInterface
{

    /**
     * @var Setup\SampleData\Executor
     */
    private $executor;

    /**
     * @var Updater
     */
    private $updater;

    /**
     * @var \Magento\Framework\App\State
     */
    private $state;

    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var PageRepositoryInterface
     */
    protected $pageRepository;

    /**
     * @var UrlRewriteCollectionFactory
     */
    protected $urlRewriteCollectionFactory;


    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param Setup\SampleData\Executor $executor
     * @param Updater $updater
     * @param \Magento\Framework\App\State $state
     * @param PageFactory $pageFactory
     * @param PageRepositoryInterface $pageRepository
     * @param UrlRewriteCollectionFactory $urlRewriteCollectionFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        Setup\SampleData\Executor $executor,
        Updater $updater,
        \Magento\Framework\App\State $state,
        PageFactory $pageFactory,
        PageRepositoryInterface $pageRepository,
        UrlRewriteCollectionFactory $urlRewriteCollectionFactory
    ){
        $this->moduleDataSetup = $moduleDataSetup;
        $this->executor = $executor;
        $this->updater = $updater;
        $this->state = $state;
        $this->pageFactory = $pageFactory;
        $this->pageRepository = $pageRepository;
        $this->urlRewriteCollectionFactory = $urlRewriteCollectionFactory;
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

        /** Remove the old weltpixel-icons page, new one will be added instead of that */
        $page = $this->pageFactory->create();
        $pageCollection = $page->getCollection()->addFieldToFilter('identifier', 'weltpixel-icons');

        if ($pageCollection->getSize()) {
            $page =  $pageCollection->getFirstItem();
            $this->pageRepository->delete($page);

            $urlCollection = $this->urlRewriteCollectionFactory->create();
            $urlCollection->addFieldToFilter('request_path', 'weltpixel-icons');

            foreach ($urlCollection->getItems() as $item) {
                $item->delete();
            }
        }

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.1.3';
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
            UpdateSampleData2::class
        ];
    }
}
