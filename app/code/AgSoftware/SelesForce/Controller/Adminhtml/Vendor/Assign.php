<?php
/**
 * Copyright © none All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace AgSoftware\SelesForce\Controller\Adminhtml\Vendor;

use Magento\Backend\App\Action\Context;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Controller\Adminhtml\Index\AbstractMassAction;
use Magento\Customer\Model\CustomerFactory;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Newsletter\Model\SubscriptionManagerInterface;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;

class Assign extends AbstractMassAction implements HttpPostActionInterface
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

     /**
      * @param Context $context
      * @param Filter $filter
      * @param CollectionFactory $collectionFactory
      * @param CustomerRepositoryInterface $customerRepository
      * @param SubscriptionManagerInterface $subscriptionManager
      */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        CustomerFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        SubscriptionManagerInterface $subscriptionManager,
        \Magento\Framework\App\RequestInterface $request
    ) {
        parent::__construct($context, $filter, $collectionFactory);
        $this->requets = $request;
        //$this->resultPageFactory = $resultPageFactory;
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
    }

    /**
     * Execute view action
     *
     * @return ResultInterface
     */
    /*public function execute()
    {
        $data = $this->requets->getParams();
        print_r($data);
        die;

        $redirect = $this->resultPageFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $customerId = $this->customerSession->getCustomer()->getId();
        if (!$customerId) {
            $redirect->setUrl('/customer/account/login');
            return $redirect;
        }
    }*/

    /**
     * Customer mass subscribe action
     *
     * @param AbstractCollection $collection
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    protected function massAction(AbstractCollection $collection){

        $customersUpdated = 0;
        $seller = $this->requets->getParam('user');
        foreach ($collection->getAllIds() as $customerId) {
            $customer = $this->customerFactory->create()->load($customerId);
            if($seller != 0 ) {
                $sellers = $customer->getData('telemarketers')?
                    explode(',', $customer->getData('telemarketers')):[];
                $sellers[] = $seller;
                $sellers = array_unique($sellers);
                $customer->setData('telemarketers',implode(',',$sellers));
            }else{
                $customer->setData('telemarketers','');
            }
            $customer->save();

            $customersUpdated++;
        }

        if ($customersUpdated) {
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) were updated.', $customersUpdated));
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('customer/index/index');

        return $resultRedirect;
    }
}

