<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace AgSoftware\SelesForce\Plugin\Magento\Customer\Model\ResourceModel\Grid;

use Magento\Authorization\Model\Acl\Role\Group as RoleGroup;

/**
 * Admin role data grid collection
 */
class Collection extends \Magento\Authorization\Model\ResourceModel\Role\Collection
{
    /**
     * Prepare select for load
     *
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->addFieldToFilter('role_type', ['in'=>[RoleGroup::ROLE_TYPE,'S']]);
        return $this;
    }
}
