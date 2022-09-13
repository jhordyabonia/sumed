<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace AgSoftware\SalesForce\Controller\Adminhtml\Vendor;

use Magento\Authorization\Model\Acl\Role\Group as RoleGroup;

abstract class Role extends \Magento\User\Controller\Adminhtml\User\Role
{
    /**
     * Initialize role model by passed parameter in request
     *
     * @param string $requestVariable
     * @return \Magento\Authorization\Model\Role
     */
    protected function _initRole($requestVariable = 'rid')
    {
        throw new \Exception("here");
        $role = $this->_roleFactory->create()->load($this->getRequest()->getParam($requestVariable));
        // preventing edit of relation role
        if ($role->getId() && ($role->getRoleType() != RoleGroup::ROLE_TYPE || $role->getRoleType() != 'S')){
            $role->unsetData($role->getIdFieldName());
        }

        $this->_coreRegistry->register('current_role', $role);
        return $this->_coreRegistry->registry('current_role');
    }
}
