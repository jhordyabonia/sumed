<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace AgSoftware\SelesForce\Model;

use Magento\Framework\Phrase;
use Magento\Framework\UrlInterface;
use Magento\Customer\Model\ResourceModel\Group\CollectionFactory;

/**
 * Class Options for Mass Action Group
 *
 * Disable template needed for customers
 */
class Options implements \JsonSerializable
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Additional options params
     *
     * @var array
     */
    protected $data;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Base URL for subactions
     *
     * @var string
     */
    protected $urlPath;

    /**
     * Param name for subactions
     *
     * @var string
     */
    protected $paramName;

    /**
     * Additional params for subactions
     *
     * @var array
     */
    protected $additionalData = [];

    /**
     * Constructor
     *
     * @param CollectionFactory $collectionFactory
     * @param UrlInterface $urlBuilder
     * @param array $data
     */
    public function __construct(
        \AgSoftware\SelesForce\Model\Attribute\Source\Telemarketers $collectionFactory,
        UrlInterface $urlBuilder,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->data = $data;
        $this->urlBuilder = $urlBuilder;
        /*$this->urlPath = $urlBuilder->getCurrentUrl();
        $this->paramName = 'user';*/
    }

    /**
     * Get action options
     *
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        if ($this->options === null) {
            $options = $this->collectionFactory->getAllOptions();
            $options[] = ['value'=>0,'label'=>__('Ninguno')];
            $this->prepareData();
            foreach ($options as $optionCode) {
                $this->options[$optionCode['value']] = [
                    'type' => 'seller_' . $optionCode['value'],
                    'label' => __($optionCode['label']),
                    '__disableTmpl' => true
                ];

                //if ($this->urlPath){
                     if($this->paramName) {
                        $this->options[$optionCode['value']]['url'] = $this->urlBuilder->getUrl(
                            'salesforce/vendor/index',//$this->urlPath,
                            [$this->paramName => $optionCode['value']]
                        );
                    }else{
                         $this->options[$optionCode['value']]['url'] = $this->urlBuilder->getUrl(
                             'salesforce/vendor/assign',//$this->urlPath,
                             ['user' => $optionCode['value']]
                         );
                     }
                //}

                $this->options[$optionCode['value']] = array_merge_recursive(
                    $this->options[$optionCode['value']],
                    $this->additionalData
                );
            }

            $this->options = array_values($this->options);
        }

        return $this->options;
    }

    /**
     * Prepare addition data for subactions
     *
     * @return void
     */
    protected function prepareData()
    {
        foreach ($this->data as $key => $value) {
            switch ($key) {
                case 'urlPath':
                    $this->urlPath = $value;
                    break;
                case 'paramName':
                    $this->paramName = $value;
                    break;
                case 'confirm':
                    foreach ($value as $messageName => $message) {
                        $this->additionalData[$key][$messageName] = (string) new Phrase($message);
                    }
                    break;
                default:
                    $this->additionalData[$key] = $value;
                    break;
            }
        }
    }
}
