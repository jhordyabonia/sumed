<?php
/**
 * Copyright © none All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Sumed\Envios\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface EnviosRepositoryInterface
{

    /**
     * Save envios
     * @param \Sumed\Envios\Api\Data\EnviosInterface $envios
     * @return \Sumed\Envios\Api\Data\EnviosInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Sumed\Envios\Api\Data\EnviosInterface $envios
    );

    /**
     * Retrieve envios
     * @param string $enviosId
     * @return \Sumed\Envios\Api\Data\EnviosInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($enviosId);

    /**
     * Retrieve envios matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Sumed\Envios\Api\Data\EnviosSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete envios
     * @param \Sumed\Envios\Api\Data\EnviosInterface $envios
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Sumed\Envios\Api\Data\EnviosInterface $envios
    );

    /**
     * Delete envios by ID
     * @param string $enviosId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($enviosId);
}

