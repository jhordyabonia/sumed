<?php

namespace Sumed\Catalog\Console\Command;

use Exception;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;

class Indexer extends Command
{
    const FILE_FIX = "./var/log/product-indexer-sumed-attribute.log";
    const STORE_ID = 1;
    const STORE_ID_COMMERCE = 2;
    /**
     * @var ProductFactory
     */
    protected $_productFactory;

    protected $_cached;

    /**
     * @param ProductFactory $productFactory
     * @param string|null $name
     */
    public function __construct(
        ProductFactory $productFactory,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository,
        \Magento\Eav\Model\Entity\Attribute\Source\TableFactory $tableFactory,
        \Magento\Eav\Api\AttributeOptionManagementInterface $attributeOptionManagement,
        \Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory $optionLabelFactory,
        \Magento\Eav\Api\Data\AttributeOptionInterfaceFactory $optionFactory,
        \Magento\Framework\App\State $state,
        ProductRepositoryInterface $productRepository,
        string $name = null
        ) {
        $this->state = $state;
        $this->_productFactory = $productFactory;
        $this->_productRepository = $productRepository;

        $this->attributeRepository = $attributeRepository;
        $this->tableFactory = $tableFactory;
        $this->attributeOptionManagement = $attributeOptionManagement;
        $this->optionLabelFactory = $optionLabelFactory;
        $this->optionFactory = $optionFactory;
        $this->_cached = [];
        parent::__construct($name);
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML); // or \Magento\Framework\App\Area::AREA_FRONTEND, depending on your needs

    }
    /**
     * Get attribute by code.
     *
     * @param string $attributeCode
     * @return \Magento\Catalog\Api\Data\ProductAttributeInterface
     */
    public function getAttribute($attributeCode)
    {
        return $this->attributeRepository->get($attributeCode);
    }

    /**
     * Find or create a matching attribute option
     *
     * @param string $attributeCode Attribute the option should exist in
     * @param string $label Label to find or add
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createOrGetId($attributeCode, $label)
    {
        if (empty($label)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Label for %1 must not be empty.', $attributeCode)
            );
        }

        $optionId = $this->getOptionId($attributeCode, $label);

        if (!$optionId) {
            /** @var \Magento\Eav\Model\Entity\Attribute\OptionLabel $optionLabel */
            $optionLabel = $this->optionLabelFactory->create();
            $optionLabel->setStoreId(0);
            $optionLabel->setLabel($label);

            $option = $this->optionFactory->create();
            $option->setLabel($optionLabel->getLabel());
            $option->setStoreLabels([$optionLabel]);
            $option->setSortOrder(0);
            $option->setIsDefault(false);

            $this->attributeOptionManagement->add(
                \Magento\Catalog\Model\Product::ENTITY,
                $this->getAttribute($attributeCode)->getAttributeId(),
                $option
            );

            $optionId = $this->getOptionId($attributeCode, $label, true);
        }

        return $optionId;
    }

    /**
     * Find the ID of an option matching $label, if any.
     *
     * @param string $attributeCode Attribute code
     * @param string $label Label to find
     * @param bool $force If true, will fetch the options even if they're already cached.
     * @return int|false
     */
    public function getOptionId($attributeCode, $label, $force = false)
    {
        if (isset($this->attributeValues[$attributeCode][ $label ])) {
            return $this->attributeValues[$attributeCode][ $label ];
        }
        /** @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute */
        $attribute = $this->getAttribute($attributeCode);

        if ($force === true || !isset($this->attributeValues[$attributeCode])) {
            $this->attributeValues[$attributeCode] = [];

            /** @var \Magento\Eav\Model\Entity\Attribute\Source\Table $sourceModel */
            $sourceModel = $this->tableFactory->create();
            $sourceModel->setAttribute($attribute);

            foreach ($sourceModel->getAllOptions() as $option) {
                $this->attributeValues[$attributeCode][ $option['label'] ] = $option['value'];
            }
        }

        if (isset($this->attributeValues[$attributeCode][ $label ])) {
            return $this->attributeValues[$attributeCode][ $label ];
        }

        return false;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName("sumed:product:index");
        $this->setDescription("Index Attributes Sumed Product ");
        $this->setDefinition([
            new InputOption(
                'sku',
                's',
                InputOption::VALUE_REQUIRED,
                __('Product Sku')
            ),
            new InputOption(
                'all',
                'a',
                InputOption::VALUE_NONE,
                __('All products')
            )
        ]);
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sku = $input->getOption('sku');
        $all = $input->getOption('all');
        $countSuccess = $countError = 0;
        if($all){
            $collectionProduct = $this->_productFactory->create()->getCollection()
                                    ->addAttributeToSelect(["segment_1","segment_2","brand","ansiolitico","ioma","pami","name"]);

            foreach ($collectionProduct->load() as $product){;
                try {
                    $this->updateProduct($product);
                    $countSuccess++;
                }catch (\Exception $e){
                    $countError++;
                }
            }

            $textSucces = "$countSuccess products updated!\n";
            $textError = "$countError products no updated!\n";
            echo $textSucces;
            echo $textError;

        }elseif($sku){
            $product = $this->_productRepository->get("$sku");
            if($this->updateProduct($product)){
                echo "$sku Update!\n";
            }
        }else{
            throw new \Exception("SKU is required!");
        }
    }

    private function updateProduct($product){
        $value = $product->getData('segment_1');
        if(intval($value) <= 0 && $value) {
            $product->setData('Segmento_1', $this->createOrGetId('Segmento_1', $value));
        }
        $value = $product->getData('segment_2');
        if(intval($value) <= 0 && $value) {
            $product->setData('Segmento_2', $this->createOrGetId('Segmento_2', $value));
        }
        $value = $product->getData('brand');
        if(intval($value) <= 0 && $value) {
            $product->setData('Proveedor', $this->createOrGetId('Proveedor', $value));
        }
        $value = $product->getData('ansiolitico');
        if(intval($value) <= 0 && $value) {
            $product->setData('Rubro', $this->createOrGetId('Rubro', $value));
        }
        $coberture = $product->getData('ioma')?'IOMA':($product->getData('pami')?'PAMI':false);
        if($coberture) {
            $product->setData('Cobertura', $this->createOrGetId('Cobertura', $coberture));
        }

       return $this->_productRepository->save($product);
    }
}
