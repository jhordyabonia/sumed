<?php
/**
 * Copyright © none All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace AgSoftware\Login\Block;

class Login extends \Magento\Backend\Block\Template
{
    private \Magento\Customer\Model\Session $customerSession;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Customer\Model\Session $session,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->customerSession = $session;
        parent::__construct($context, $data);
    }
    public function isLoggedIn(){
        return $this->customerSession->isLoggedIn();
    }
}

