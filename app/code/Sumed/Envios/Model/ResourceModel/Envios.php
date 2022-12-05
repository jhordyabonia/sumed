<?php
/**
 * Copyright © none All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Sumed\Envios\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Envios extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('sumed_envios_envios', 'envios_id');
    }
}

