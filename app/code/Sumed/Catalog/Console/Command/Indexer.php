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
        /** @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute */
        $attribute = $this->getAttribute($attributeCode);

        // Build option array if necessary
        if ($force === true || !isset($this->attributeValues[ $attribute->getAttributeId() ])) {
            $this->attributeValues[ $attribute->getAttributeId() ] = [];

            // We have to generate a new sourceModel instance each time through to prevent it from
            // referencing its _options cache. No other way to get it to pick up newly-added values.

            /** @var \Magento\Eav\Model\Entity\Attribute\Source\Table $sourceModel */
            $sourceModel = $this->tableFactory->create();
            $sourceModel->setAttribute($attribute);

            foreach ($sourceModel->getAllOptions() as $option) {
                $this->attributeValues[ $attribute->getAttributeId() ][ $option['label'] ] = $option['value'];
            }
        }

        // Return option ID if exists
        if (isset($this->attributeValues[ $attribute->getAttributeId() ][ $label ])) {
            return $this->attributeValues[ $attribute->getAttributeId() ][ $label ];
        }

        // Return false if does not exist
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
            ),
            new InputOption(
                'file',
                'f',
                InputOption::VALUE_NONE,
                __('json-file sku-list: '.self::FILE_FIX)
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
        $file = $input->getOption('file');
        $countSuccess = $countError = 0;
        $skuSuccess = $skuError = [];
        if($all){
            $collectionProduct = $this->_productFactory->create()->getCollection();
            if($file){
                $skulist = json_decode(file_get_contents(self::FILE_FIX),true);
                $skulist = $skulist['data'];
                $collectionProduct->addFieldToFilter('sku',['in'=>$skulist]);
            }
            foreach ($collectionProduct->load() as $product){
                $value = $product->getData('segment_1');
                if(!is_int($value) && $value) {
                    $product->setData('Segmento_1', $this->createOrGetId('Segmento_1', $value));
                }
                $value = $product->getData('segment_2');
                if(!is_int($value) && $value) {
                    $product->setData('Segmento_2', $this->createOrGetId('Segmento_2', $value));
                }

                $coberture = $product->getData('ioma')?'IOMA':($product->getData('pami')?'PAMI':'');
                $product->setData('COBERTURA', $this->createOrGetId('COBERTURA', $coberture));
                try {
                    $this->_productRepository->save($product);
                    $skuSuccess[] = $product->getSku();
                    $countSuccess++;
                }catch (\Exception $e){
                    $countError++;
                    $skuError[] = $product->getSku();

                }
            }

            $textSucces = "$countSuccess products updated!\n";
            $textError = "$countError products no updated!\n";
            $nameLog =  self::FILE_FIX;
            $content = ['message'=>$countError<$countSuccess?$textError:$textSucces];
            $content['data'] = $countError<$countSuccess?$skuError:$skuSuccess;
            file_put_contents($nameLog,json_encode($content));

            echo $textSucces;
            echo $textError;
            echo "See report on $nameLog\n";

        }elseif($sku){
            $product = $this->_productRepository->get("$sku");
            $value = $product->getData('segment_1') ;
            if(!is_int($value) && $value){
//                $product->addAttributeUpdate('Segmento_1',$this->createOrGetId('Segmento_1',$value),0);
                $product->setData('Segmento_1',$this->createOrGetId('Segmento_1',$value),0);
                if($this->_productRepository->save($product)){
                    echo "$sku Update!\n";
                }
            }
        }else{
            throw new \Exception("SKU is required!");
        }
    }
}
