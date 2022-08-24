<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

use Magento\CatalogPermissions\Model\Permission;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use Magento\Framework\Registry;

/** @var ObjectManager $objectManager */
$objectManager = Bootstrap::getObjectManager();

/** @var Registry $registry */
$registry = $objectManager->get(Registry::class);

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

/** @var Permission $permission */
$permission = $objectManager->create(Permission::class);
$permission->load(100);
if ($permission->getId()) {
    $permission->delete();
}

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);
