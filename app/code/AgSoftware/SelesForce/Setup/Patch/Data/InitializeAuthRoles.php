<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace AgSoftware\SelesForce\Setup\Patch\Data;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

/**
 * Class InitializeAuthRoles
 * @package Magento\Authorization\Setup\Patch
 */
class InitializeAuthRoles implements DataPatchInterface, PatchVersionInterface
{
    /**
     * All the group roles are prepended by S
     *
     */
    const ROLE_TYPE = 'S';
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var \Magento\Authorization\Setup\AuthorizationFactory
     */
    private $authFactory;

    /**
     * InitializeAuthRoles constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param \Magento\Authorization\Setup\AuthorizationFactory $authorizationFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Authorization\Model\RoleFactory $roleFactory,
        \Magento\Authorization\Model\RulesFactory $rulesFactory,
        \Magento\Authorization\Setup\AuthorizationFactory $authorizationFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->roleFactory = $roleFactory;
        $this->rulesFactory = $rulesFactory;
        $this->authFactory = $authorizationFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $roleCollection = $this->authFactory->createRoleCollection()
            ->addFieldToFilter('parent_id', 0)
            ->addFieldToFilter('tree_level', 1)
            ->addFieldToFilter('role_type', 'S',/*self::ROLE_TYPE*/)
            ->addFieldToFilter('user_id', 0)
            ->addFieldToFilter('user_type', UserContextInterface::USER_TYPE_ADMIN)
            //->addFieldToFilter('role_name', 'Sales Force')
        ;

        if ($roleCollection->count() == 0) {
            $admGroupRole = $this->roleFactory->create()->setData(
                [
                    'parent_id' => 0,
                    'tree_level' => 1,
                    'sort_order' => 1,
                    'role_type' => 'G',//self::ROLE_TYPE,
                    'user_id' => 0,
                    'user_type' => UserContextInterface::USER_TYPE_ADMIN,
                    'role_name' => 'Sales Force',
                ]
            )->save();
        } else {
            /** @var \Magento\Authorization\Model\ResourceModel\Role $item */
            foreach ($roleCollection as $item) {
                $admGroupRole = $item;
                break;
            }
        }

        $rulesCollection = $this->authFactory->createRulesCollection()
            ->addFieldToFilter('role_id', $admGroupRole->getId());

        if ($rulesCollection->count() == 0) {
            $resource=['Magento_Backend::admin',
                'Magento_Sales::sales',
                'Magento_Sales::create',
                'Magento_Sales::actions_view', //you will use resource id which you want tp allow
                'Magento_Sales::cancel'
            ];
            /* Array of resource ids which we want to allow this role*/
            $this->rulesFactory->create()->setRoleId($admGroupRole->getId())->setResources($resource)->saveRel();
        } else {
            /** @var \Magento\Authorization\Model\Rules $rule */
            foreach ($rulesCollection as $rule) {
                $rule->setData('resource_id', 'Magento_Sales::sales')->save();
            }
        }
        //$admGroupRole->setData('role_type',self::ROLE_TYPE)->save();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.0.1';
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
