<?php
/**
 * Copyright © none All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace AgSoftware\Login\Plugin;

use Magento\Framework\App\Request\DataPersistorInterface;

class CustomerRedirect
{
    public function __construct(
        \Magento\Customer\Model\Session $session,
        DataPersistorInterface $dataPersistor
    ){
        $this->session = $session;
        $this->dataPersistor = $dataPersistor;
    }
    public function beforeExecute($subject)
    {
        $customer = $this->session->getCustomer();
        $this->dataPersistor->set('customer',[
            'firstname' =>   $customer->getfirstname(),
            'lastname'  =>    $customer->getLastname(),
            'email'  =>    $customer->getEmail()
            ]);
    }
}
