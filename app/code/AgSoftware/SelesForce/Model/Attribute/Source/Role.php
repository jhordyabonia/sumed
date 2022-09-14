<?php
/**
 * Copyright © none All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace AgSoftware\SelesForce\Model\Attribute\Source;

class Role extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    private \Magento\Authorization\Model\RoleFactory $roleFactory;

    public function __construct(
        \Magento\Authorization\Model\RoleFactory $roleFactory
    ){
        $this->roleFactory = $roleFactory;
    }

    /**
     * getAllOptions
     *
     * @return array
     */

    public function getAllOptions()
    {
        $this->_options = [];
        foreach ($this->roleFactory->create()->getCollection() as $rol){
            $this->_options[] =["value"=>$rol->getRoleId(),"label"=>$rol->getRoleName()];
        }
        return $this->_options;
    }
}

