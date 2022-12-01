<?php
/**
 * Copyright © Balloon Group 2022 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace BalloonGroup\CuentaCorriente\Controller\Account;


class Cuentacorriente extends \Magento\Framework\App\Action\Action
{

    public function execute()
    {
        return  $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
    }
}

