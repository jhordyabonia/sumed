<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\GraphQl\SharedCatalog;

use Magento\TestFramework\TestCase\GraphQlAbstract;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\GraphQl\GetCustomerAuthenticationHeader;
use Magento\CatalogPermissions\Model\Indexer\Category\Processor as CategoryPermissionsIndexer;
use Magento\CatalogPermissions\Model\Indexer\Product\Processor as ProductPermissionsIndexer;
use Magento\TestFramework\ObjectManager;

/**
 * Filter category list test
 */
class SharedCatalogPermissionsTest extends GraphQlAbstract
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var GetCustomerAuthenticationHeader
     */
    private $getCustomerAuthenticationHeader;

    /**
     * Set Up
     */
    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->getCustomerAuthenticationHeader = $this->objectManager->get(GetCustomerAuthenticationHeader::class);
    }

    /**
     * Response needs to have exact count and category by name
     *
     * @magentoConfigFixture default_store catalog/magento_catalogpermissions/enabled 1
     * @magentoConfigFixture default_store catalog/magento_catalogpermissions/enabled 1
     * @magentoConfigFixture default_store catalog/magento_catalogpermissions/grant_catalog_category_view 1
     * @magentoConfigFixture default_store catalog/magento_catalogpermissions/grant_catalog_product_price 1
     * @magentoConfigFixture default_store catalog/magento_catalogpermissions/grant_checkout_items 1
     * @magentoConfigFixture default_store btob/website_configuration/company_active 1
     * @magentoConfigFixture default_store btob/website_configuration/sharedcatalog_active 1
     * @magentoApiDataFixture Magento/SharedCatalog/_files/permissions/multiple_shared_catalogs.php
     * @magentoApiDataFixture Magento/SharedCatalog/_files/permissions/companies_with_admin.php
     * @magentoApiDataFixture Magento/SharedCatalog/_files/permissions/categories.php
     * @magentoApiDataFixture Magento/SharedCatalog/_files/permissions/enable_parent_category.php
     */
    public function testCategoryListCountRightChildrenAndProducts()
    {
        $this->objectManager
            ->create(CategoryPermissionsIndexer::class)
            ->reindexAll();
        $this->objectManager
            ->create(ProductPermissionsIndexer::class)
            ->reindexAll();

        $currentEmail = 'admin@0company.com';
        $currentPassword = 'password';
        $response = $this->graphQlQuery(
            $this->getListQuery(),
            [],
            '',
            $this->objectManager->get(GetCustomerAuthenticationHeader::class)->execute($currentEmail, $currentPassword)
        );
        $this->assertCount(1, $response['categoryList']);
        $this->assertEquals(1, $response['categoryList'][0]['children_count']);
        $this->assertEquals(3, $response['categoryList'][0]['children'][0]['product_count']);
    }

    /**
     * Get category list query with children and products_count
     *
     * @return string
     */
    private function getListQuery(): string
    {
        $query = <<<QUERY
{
  categoryList(filters: {ids: {eq: "2"}}) {
    uid
    name
    product_count
    children_count
    children{
      product_count
      name
      products{
        items{
          sku
          name
        }
      }
    }
    products{
      items{
        sku
      }
    }
  }
}
QUERY;
        return $query;
    }
}
