<?php
/**
 * Copyright © none All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace AgSoftware\SelesForce\Plugin\Magento\Customer\Model\ResourceModel;

class CustomerCollection
{
    protected \Magento\Backend\Model\Auth\Session $authSession;
    protected \Magento\Authorization\Model\RoleFactory $roleFactory;

    public function __construct(
        \Magento\Authorization\Model\RoleFactory $roleFactory,
        \Magento\Backend\Model\Auth\Session $authSession)
    {
        $this->roleFactory = $roleFactory;
        $this->authSession = $authSession;
    }

    public function afterGetData(
        \Magento\Customer\Ui\Component\DataProvider $subject,
        $result
    ) {
        if($this->authSession->isLoggedIn()) {
            $vendor = $this->authSession->getUser()->getId();
            $rols = $this->authSession->getAcl()->getRoles();
            //throw new \Exception( print_r($rols,true)." $vendor ---");
            if(count($rols)==1){
                if($this->roleFactory->create()->load($rols[0])->getRoleType()=='S'){
                    throw new \Exception( print_r($rols,true)." $vendor ---");
                    $result->addAttrubuteTofilter(['telemarketers' => ['Like' => "%,$vendor,%"]]);
                }
            }
        }
        return $result;
    }
}

