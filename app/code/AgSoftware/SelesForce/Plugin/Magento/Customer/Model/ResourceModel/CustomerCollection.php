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
        \Magento\Framework\Api\Search\FilterGroup $filterGroups,
        \Magento\Framework\Api\Filter $filter,
        \Magento\Authorization\Model\RoleFactory $roleFactory,
        \Magento\Backend\Model\Auth\Session $authSession)
    {
        $this->filterGroups = $filterGroups;
        $this->filter = $filter;
        $this->roleFactory = $roleFactory;
        $this->authSession = $authSession;
    }

    public function beforeGetData(
        \Magento\Customer\Ui\Component\DataProvider $subject
    ) {
        if($this->authSession->isLoggedIn()) {
            $vendor = $this->authSession->getUser()->getId();
            $rols = $this->authSession->getAcl()->getRoles();
            if(count($rols)==1){
                if($this->roleFactory->create()->load($rols[0])->getRoleId()=='12'){
                    //throw new \Exception( print_r($rols,true)." $vendor ---");
                    $this->filter->setField('telemarketers');
                    $this->filter->setValue("%,$vendor,%");
                    $this->filter->setConditionType('like');
                    $subject->addFilter($this->filter);
                }
            }
        }
        return [];
    }
}

