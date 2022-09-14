<?php
/**
 * Copyright © none All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace AgSoftware\SelesForce\Plugin\Magento\Customer\Model\ResourceModel;

use Magento\Sales\Model\ResourceModel\Order\Customer\Collection;
class CustomerCollection
{
    protected \Magento\Backend\Model\Auth\Session $authSession;
    protected \Magento\Authorization\Model\RoleFactory $roleFactory;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Api\Search\FilterGroup $filterGroups,
        \Magento\Framework\Api\Filter $filter,
        \Magento\Authorization\Model\RoleFactory $roleFactory,
        \Magento\Backend\Model\Auth\Session $authSession)
    {
        $this->scopeConfig = $scopeConfig;
        $this->filterGroups = $filterGroups;
        $this->filter = $filter;
        $this->roleFactory = $roleFactory;
        $this->authSession = $authSession;
    }

    public function after__construct(\Magento\Customer\Model\ResourceModel\Customer\Collection $subject): array
    {
        if($this->authSession->isLoggedIn()) {
            $vendor = $this->authSession->getUser()->getId();
            $subject->addAttributeToSelect('*')
                ->addAttributeToFilter(
                    'telemarketers', ['like' => "%$vendor,%"]
                );
        }
         return [];
    }
    public function beforeGetData(
        \Magento\Customer\Ui\Component\DataProvider $subject
    ) {
        if($this->authSession->isLoggedIn()) {
            $vendor = $this->authSession->getUser()->getId();
            $rols = $this->authSession->getAcl()->getRoles();
            //throw new \Exception( print_r($rols,true)." $vendor ---".$this->scopeConfig->getValue('agsoftware/salesforce/rol'));
            if(count($rols)==1){
                $rolSaleForce = $this->scopeConfig->getValue('agsoftware/salesforce/rol');
                if($this->roleFactory->create()->load($rols[0])->getRoleId() == $rolSaleForce) {
                    throw new \Exception( print_r($rols,true)." $vendor ---");
                    $this->filter->setField('telemarketers');
                    $this->filter->setValue("%$vendor,%");
                    $this->filter->setConditionType('like');
                    $subject->addFilter($this->filter);
                }
            }
        }
        return [];
    }
}

