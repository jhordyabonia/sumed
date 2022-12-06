<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Sumed\Envios\Model;

class CheckoutConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    public function __construct(
        \Magento\Customer\Model\Session $session,
        \Sumed\Envios\Model\EnviosFactory $enviosFactory
    ) {
        $this->session = $session;
        $this->enviosFactory = $enviosFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $config = [];
        $customer = $this->session->getCustomer();
        $codigoEntrega = $customer->getData("u_contraentrega");
        $config['sumed_envios'] = $this->enviosFactory->create()
                                        ->load($codigoEntrega,"codigo")
                                        ->getData();
        return $config;
    }
}
