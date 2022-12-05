<?php
/**
 * Copyright © none All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Sumed\Envios\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Sumed\Envios\Api\Data\EnviosInterface;
use Sumed\Envios\Api\Data\EnviosInterfaceFactory;
use Sumed\Envios\Api\Data\EnviosSearchResultsInterfaceFactory;
use Sumed\Envios\Api\EnviosRepositoryInterface;
use Sumed\Envios\Model\ResourceModel\Envios as ResourceEnvios;
use Sumed\Envios\Model\ResourceModel\Envios\CollectionFactory as EnviosCollectionFactory;

class EnviosRepository implements EnviosRepositoryInterface
{

    /**
     * @var Envios
     */
    protected $searchResultsFactory;

    /**
     * @var EnviosInterfaceFactory
     */
    protected $enviosFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var ResourceEnvios
     */
    protected $resource;

    /**
     * @var EnviosCollectionFactory
     */
    protected $enviosCollectionFactory;


    /**
     * @param ResourceEnvios $resource
     * @param EnviosInterfaceFactory $enviosFactory
     * @param EnviosCollectionFactory $enviosCollectionFactory
     * @param EnviosSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceEnvios $resource,
        EnviosInterfaceFactory $enviosFactory,
        EnviosCollectionFactory $enviosCollectionFactory,
        EnviosSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->enviosFactory = $enviosFactory;
        $this->enviosCollectionFactory = $enviosCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(EnviosInterface $envios)
    {
        try {
            $this->resource->save($envios);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the envios: %1',
                $exception->getMessage()
            ));
        }
        return $envios;
    }

    /**
     * @inheritDoc
     */
    public function get($enviosId)
    {
        $envios = $this->enviosFactory->create();
        $this->resource->load($envios, $enviosId);
        if (!$envios->getId()) {
            throw new NoSuchEntityException(__('envios with id "%1" does not exist.', $enviosId));
        }
        return $envios;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->enviosCollectionFactory->create();
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(EnviosInterface $envios)
    {
        try {
            $enviosModel = $this->enviosFactory->create();
            $this->resource->load($enviosModel, $envios->getEnviosId());
            $this->resource->delete($enviosModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the envios: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($enviosId)
    {
        return $this->delete($this->get($enviosId));
    }
}

