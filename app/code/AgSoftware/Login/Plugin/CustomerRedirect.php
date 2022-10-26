<?php
/**
 * Copyright © none All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace AgSoftware\Login\Plugin;

class CustomerRedirect extends \Magento\Customer\Controller\Account\LoginPost
{
    public function execute()
    {
        if ($this->session->isLoggedIn() || !$this->formKeyValidator->validate($this->getRequest())) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }
        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    $customer = $this->customerAccountManagement->authenticate($login['username'], $login['password']);
                    $this->session->setCustomerDataAsLoggedIn($customer);
                    $redirectUrl = "/home-commerce-logged";//$this->scopeConfig->getValue('customer/startup/redirect_dashboard')

                    if($redirectUrl) {
                        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $resultRedirect->setUrl($redirectUrl);
                        return $resultRedirect;
                    }
                } catch (\Exception $e) {}
            } else {
            }
        }

        return parent::execute();
    }
}
