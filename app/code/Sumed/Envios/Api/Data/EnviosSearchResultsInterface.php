<?php
/**
 * Copyright © none All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Sumed\Envios\Api\Data;

interface EnviosSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get envios list.
     * @return \Sumed\Envios\Api\Data\EnviosInterface[]
     */
    public function getItems();

    /**
     * Set Codigo list.
     * @param \Sumed\Envios\Api\Data\EnviosInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

