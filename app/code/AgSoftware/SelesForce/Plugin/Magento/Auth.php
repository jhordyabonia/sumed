<?php
/**
 * Copyright © none All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace AgSoftware\SelesForce\Plugin\Magento;

use Magento\Authorization\Model\Acl\Role\Group as RoleGroup;
use Magento\Authorization\Model\ResourceModel\Role\Collection as CollectionAuth;

class Auth
{
    /**
     * Set roles filter
     *
     * @return  CollectionAuth
     */
    public function aroundSetRolesFilter(
        CollectionAuth $subject,
                        $proceed
    )
    {
        $subject->addFieldToFilter('role_type', ['in'=>[RoleGroup::ROLE_TYPE,'S']]);
        return $subject;
    }
}

