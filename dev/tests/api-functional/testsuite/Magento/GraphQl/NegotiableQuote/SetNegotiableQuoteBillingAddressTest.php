<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\GraphQl\NegotiableQuote;

use Exception;
use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Company\Api\Data\CompanyInterface;
use Magento\Company\Api\Data\CompanyInterfaceFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\AuthenticationException;
use Magento\Integration\Api\CustomerTokenServiceInterface;
use Magento\NegotiableQuote\Api\NegotiableQuoteRepositoryInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\GraphQlAbstract;

/**
 * Test coverage to set negotiable quote billing address.
 */
class SetNegotiableQuoteBillingAddressTest extends GraphQlAbstract
{
    /**
     * @var CustomerTokenServiceInterface
     */
    private $customerTokenService;

    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var NegotiableQuoteRepositoryInterface
     */
    private $negotiableQuoteRepository;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    protected function setUp(): void
    {
        $objectManager = Bootstrap::getObjectManager();
        $this->customerTokenService = $objectManager->get(CustomerTokenServiceInterface::class);
        $this->quoteRepository = $objectManager->get(CartRepositoryInterface::class);
        $this->negotiableQuoteRepository = $objectManager->get(NegotiableQuoteRepositoryInterface::class);
        $this->storeManager = $objectManager->get(StoreManagerInterface::class);
        $this->customerRepository = $objectManager->get(CustomerRepositoryInterface::class);
    }

    /**
     * Test that the billing address can be set on a negotiable quote using an existing customer address id.
     *
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/company_customer_with_checkout_permissions.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/product_simple.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/negotiable_quote_by_customer_for_checkout.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/customer_address_for_customer.php
     * @magentoConfigFixture base_website btob/website_configuration/negotiablequote_active 1
     * @magentoConfigFixture base_website btob/website_configuration/company_active 1
     * @throws Exception
     */
    public function testSetBillingAddressWithCustomerAddressId(): void
    {
        $query = $this->getMutationWithCustomerAddressId('nq_customer_mask', base64_encode((string)2));
        $response = $this->graphQlMutation($query, [], '', $this->getHeaderMap());

        // Assert the quote data is present and correct
        $this->assertNotEmpty($response['setNegotiableQuoteBillingAddress']);
        $this->assertArrayHasKey('quote', $response['setNegotiableQuoteBillingAddress']);
        $this->assertEquals('nq_customer_mask', $response['setNegotiableQuoteBillingAddress']['quote']['uid']);

        // Assert the billing address data is present and correct
        $this->assertArrayHasKey('billing_address', $response['setNegotiableQuoteBillingAddress']['quote']);
        $responseBillingAddress = $response['setNegotiableQuoteBillingAddress']['quote']['billing_address'];
        $this->assertResponseFields($responseBillingAddress, [
            'firstname' => 'John',
            'lastname' => 'Smith',
            'street' => ['Green str, 67'],
            'city' => 'CityM',
            'region' => ['code' => 'AL'],
            'postcode' => 75477,
            'country' => ['code' => 'US'],
            'telephone' => 3468676,
            'company' => 'CompanyName'
        ]);
    }

    /**
     * Test that the billing address can be set on a negotiable quote and used for shipping address.
     *
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/company_customer_with_checkout_permissions.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/product_simple.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/negotiable_quote_by_customer_for_checkout.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/customer_address_for_customer.php
     * @magentoConfigFixture base_website btob/website_configuration/negotiablequote_active 1
     * @magentoConfigFixture base_website btob/website_configuration/company_active 1
     * @throws Exception
     */
    public function testSetBillingAddressWithCustomerAddressIdUseForShipping(): void
    {
        $query = $this->getMutationWithCustomerAddressIdUseForShipping('nq_customer_mask', base64_encode((string)2));
        $response = $this->graphQlMutation($query, [], '', $this->getHeaderMap());

        // Assert the quote data is present and correct
        $this->assertNotEmpty($response['setNegotiableQuoteBillingAddress']);
        $this->assertArrayHasKey('quote', $response['setNegotiableQuoteBillingAddress']);
        $this->assertEquals('nq_customer_mask', $response['setNegotiableQuoteBillingAddress']['quote']['uid']);

        // Assert the billing address data is present and correct
        $this->assertArrayHasKey('billing_address', $response['setNegotiableQuoteBillingAddress']['quote']);
        $responseBillingAddress = $response['setNegotiableQuoteBillingAddress']['quote']['billing_address'];
        $this->assertResponseFields($responseBillingAddress, [
            'firstname' => 'John',
            'lastname' => 'Smith',
            'street' => ['Green str, 67'],
            'city' => 'CityM',
            'region' => ['code' => 'AL'],
            'postcode' => 75477,
            'country' => ['code' => 'US'],
            'telephone' => 3468676,
            'company' => 'CompanyName'
        ]);

        $responseShippingAddress = $response['setNegotiableQuoteBillingAddress']['quote']['shipping_addresses'][0];
        $this->assertResponseFields($responseShippingAddress, [
            'firstname' => 'John',
            'lastname' => 'Smith',
            'street' => ['Green str, 67'],
            'city' => 'CityM',
            'region' => ['code' => 'AL'],
            'postcode' => 75477,
            'country' => ['code' => 'US'],
            'telephone' => 3468676,
            'company' => 'CompanyName'
        ]);
    }

    /**
     * Test that the shipping address can be used as billing address on a negotiable quote.
     *
     * @group ttt
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/company_customer_with_checkout_permissions.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/product_simple.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/negotiable_quote_by_customer_for_checkout.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/customer_address_for_customer.php
     * @magentoConfigFixture base_website btob/website_configuration/negotiablequote_active 1
     * @magentoConfigFixture base_website btob/website_configuration/company_active 1
     * @throws Exception
     */
    public function testSetBillingAddressWithCustomerAddressIdSameAsShipping(): void
    {
        $query = $this->getMutationWithNewAddressIdUseShipping('nq_customer_mask');
        $response = $this->graphQlMutation($query, [], '', $this->getHeaderMap());

        // Assert the quote data is present and correct
        $this->assertNotEmpty($response['setNegotiableQuoteBillingAddress']);
        $this->assertArrayHasKey('quote', $response['setNegotiableQuoteBillingAddress']);
        $this->assertEquals('nq_customer_mask', $response['setNegotiableQuoteBillingAddress']['quote']['uid']);

        // Assert the billing address data is present and correct
        $this->assertArrayHasKey('billing_address', $response['setNegotiableQuoteBillingAddress']['quote']);
        $responseBillingAddress = $response['setNegotiableQuoteBillingAddress']['quote']['billing_address'];
        $this->assertResponseFields($responseBillingAddress, [
            'firstname' => 'firstname',
            'lastname' => 'lastname',
            'street' => ['street'],
            'city' => 'Los Angeles',
            'region' => ['code' => 'CA'],
            'postcode' => 11111,
            'country' => ['code' => 'US'],
            'telephone' => 11111111
        ]);
    }

    /**
     * Test that the billing address can be set on a negotiable quote using new address input data.
     *
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/company_customer_with_checkout_permissions.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/product_simple.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/negotiable_quote_by_customer_for_checkout.php
     * @magentoConfigFixture base_website btob/website_configuration/negotiablequote_active 1
     * @magentoConfigFixture base_website btob/website_configuration/company_active 1
     * @throws Exception
     */
    public function testSetBillingAddressWithNewAddressInput(): void
    {
        $query = $this->getMutationWithNewAddressInput('nq_customer_mask');
        $response = $this->graphQlMutation($query, [], '', $this->getHeaderMap());

        // Assert the quote data is present and correct
        $this->assertNotEmpty($response['setNegotiableQuoteBillingAddress']);
        $this->assertArrayHasKey('quote', $response['setNegotiableQuoteBillingAddress']);
        $this->assertEquals('nq_customer_mask', $response['setNegotiableQuoteBillingAddress']['quote']['uid']);

        // Assert the billing address data is present and correct
        $this->assertArrayHasKey('billing_address', $response['setNegotiableQuoteBillingAddress']['quote']);
        $responseBillingAddress = $response['setNegotiableQuoteBillingAddress']['quote']['billing_address'];
        $this->assertResponseFields($responseBillingAddress, [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'street' => ['6161 West Centinela Ave.'],
            'city' => 'Culver City',
            'region' => ['code' => 'CA'],
            'postcode' => 90230,
            'country' => ['code' => 'US'],
            'telephone' => 5555555555,
            'company' => 'Magento'
        ]);
    }

    /**
     * Test that attempting to set a billing address on a negotiable quote with both an existing
     * customer address id AND new address input throws an error.
     *
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/company_customer_with_checkout_permissions.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/product_simple.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/negotiable_quote_by_customer_for_checkout.php
     * @magentoConfigFixture base_website btob/website_configuration/negotiablequote_active 1
     * @magentoConfigFixture base_website btob/website_configuration/company_active 1
     * @throws Exception
     */
    public function testSetBillingAddressWithCustomerAddressIdAndAddressInput(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'The billing address cannot contain "customer_address_uid" and "address" at the same time.'
        );

        $query = $this->getMutationWithCustomerAddressIdAndNewAddressInput(
            'nq_customer_mask',
            base64_encode((string)1)
        );
        $this->graphQlMutation($query, [], '', $this->getHeaderMap());
    }

    /**
     * Testing for guest customer token
     *
     * @magentoConfigFixture base_website btob/website_configuration/negotiablequote_active 1
     * @magentoConfigFixture base_website btob/website_configuration/company_active 1
     *
     * @throws Exception
     */
    public function testSetBillingAddressWithNoCustomerToken(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The current user is not a registered customer and cannot perform operations '
            . 'on negotiable quotes.');

        $query = $this->getMutationWithCustomerAddressId('nq_customer_mask', base64_encode('2'));
        $this->graphQlMutation($query);
    }

    /**
     * Testing for module enabled
     *
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/company_customer_with_checkout_permissions.php
     * @magentoConfigFixture base_website btob/website_configuration/negotiablequote_active 0
     * @magentoConfigFixture base_website btob/website_configuration/company_active 1
     *
     * @throws Exception
     */
    public function testSetBillingAddressNoModuleEnabled(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The Negotiable Quote module is not enabled.');

        $query = $this->getMutationWithCustomerAddressId('nq_customer_mask', base64_encode('2'));
        $this->graphQlMutation($query, [], '', $this->getHeaderMap());
    }

    /**
     * Testing for customer belongs to a company
     *
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/customer_no_company.php
     * @magentoConfigFixture base_website btob/website_configuration/negotiablequote_active 1
     * @magentoConfigFixture base_website btob/website_configuration/company_active 1
     *
     * @throws Exception
     */
    public function testSetBillingAddressCustomerNoCompany(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The current customer does not belong to a company.');

        $query = $this->getMutationWithCustomerAddressId('nq_customer_mask', base64_encode('2'));
        $this->graphQlMutation($query, [], '', $this->getHeaderMap("customernocompany@example.com"));
    }

    /**
     * Testing for feature enabled on company
     *
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/company_customer_with_checkout_permissions.php
     * @magentoConfigFixture base_website btob/website_configuration/negotiablequote_active 1
     * @magentoConfigFixture base_website btob/website_configuration/company_active 1
     *
     * @throws Exception
     */
    public function testSetBillingAddressNoCompanyFeatureEnabled(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Negotiable quotes are not enabled for the current customer\'s company.');

        /** @var CompanyInterfaceFactory $companyFactory */
        $companyFactory = Bootstrap::getObjectManager()->get(CompanyInterfaceFactory::class);
        /** @var CompanyInterface $company */
        $company = $companyFactory->create()->load('email@companyquote.com', 'company_email');
        $company->getExtensionAttributes()->getQuoteConfig()->setIsQuoteEnabled(false);
        /** @var CompanyRepositoryInterface $companyRepository */
        $companyRepository = Bootstrap::getObjectManager()->create(CompanyRepositoryInterface::class);
        $companyRepository->save($company);

        $query = $this->getMutationWithCustomerAddressId('nq_customer_mask', base64_encode('2'));
        $this->graphQlMutation($query, [], '', $this->getHeaderMap());
    }

    /**
     * Testing for manage permissions
     *
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/company_customer_with_view_permissions.php
     * @magentoConfigFixture base_website btob/website_configuration/negotiablequote_active 1
     * @magentoConfigFixture base_website btob/website_configuration/company_active 1
     *
     * @throws Exception
     */
    public function testSetBillingAddressNoCheckoutPermissions(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The current customer does not have permission to checkout negotiable quotes.');

        $query = $this->getMutationWithCustomerAddressId('nq_customer_mask', base64_encode('2'));
        $this->graphQlMutation($query, [], '', $this->getHeaderMap());
    }

    /**
     * Testing for quote ownership
     *
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/company_customer_with_checkout_permissions.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/product_simple.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/negotiable_quote.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/customer_address_for_customer.php
     * @magentoConfigFixture base_website btob/website_configuration/negotiablequote_active 1
     * @magentoConfigFixture base_website btob/website_configuration/company_active 1
     *
     * @throws Exception
     */
    public function testSetBillingAddressUnownedQuote(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Could not find a quote with the specified UID.');

        $query = $this->getMutationWithCustomerAddressId('nq_admin_mask', base64_encode('2'));
        $this->graphQlMutation($query, [], '', $this->getHeaderMap());
    }

    /**
     * Testing that quote is negotiable
     *
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/company_customer_with_checkout_permissions.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/product_simple.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/cart_with_item_for_customer.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/customer_address_for_customer.php
     * @magentoConfigFixture base_website btob/website_configuration/negotiablequote_active 1
     * @magentoConfigFixture base_website btob/website_configuration/company_active 1
     * @throws Exception
     */
    public function testSetBillingAddressNonNegotiable(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The quotes with the following UIDs are not negotiable: '
            . 'cart_item_customer_mask');

        $query = $this->getMutationWithCustomerAddressId('cart_item_customer_mask', base64_encode('2'));
        $this->graphQlMutation($query, [], '', $this->getHeaderMap());
    }

    /**
     * Testing that quote is in a valid status
     *
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/company_customer_with_checkout_permissions.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/product_simple.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/negotiable_quote_by_customer_closed.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/customer_address_for_customer.php
     * @magentoConfigFixture base_website btob/website_configuration/negotiablequote_active 1
     * @magentoConfigFixture base_website btob/website_configuration/company_active 1
     * @throws Exception
     */
    public function testSetBillingAddressBadStatus(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'The quote nq_customer_closed_mask is currently locked,'
            . ' and you cannot place an order from it at the moment.'
        );

        $query = $this->getMutationWithCustomerAddressId('nq_customer_closed_mask', base64_encode('2'));
        $this->graphQlMutation($query, [], '', $this->getHeaderMap());
    }

    /**
     * Testing that address id is valid
     *
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/company_customer_with_checkout_permissions.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/product_simple.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/negotiable_quote_by_customer_for_checkout.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/customer_address_for_customer.php
     * @magentoConfigFixture base_website btob/website_configuration/negotiablequote_active 1
     * @magentoConfigFixture base_website btob/website_configuration/company_active 1
     * @throws Exception
     */
    public function testSetBillingAddressInvalidAddressId(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Could not find a address with ID "9999"');

        $query = $this->getMutationWithCustomerAddressId('nq_customer_mask', base64_encode('9999'));
        $this->graphQlMutation($query, [], '', $this->getHeaderMap());
    }

    /**
     * Testing that address is owned by the customer
     *
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/company_customer_with_checkout_permissions.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/product_simple.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/negotiable_quote_by_customer_for_checkout.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/customer_address.php
     * @magentoConfigFixture base_website btob/website_configuration/negotiablequote_active 1
     * @magentoConfigFixture base_website btob/website_configuration/company_active 1
     * @throws Exception
     */
    public function testSetBillingAddressUnownedAddress(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Current customer does not have permission to address with ID "1"');

        $query = $this->getMutationWithCustomerAddressId('nq_customer_mask', base64_encode('1'));
        $this->graphQlMutation($query, [], '', $this->getHeaderMap());
    }

    /**
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/company_customer_with_checkout_permissions.php
     * @magentoConfigFixture base_website btob/website_configuration/negotiablequote_active 1
     * @magentoConfigFixture base_website btob/website_configuration/company_active 1
     * @throws Exception
     */
    public function testSetBillingAddressForInvalidNegotiableQuoteId(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Could not find quotes with the following UIDs: 9999");

        $negotiableQuoteQuery = $this->getMutationWithCustomerAddressId('9999', base64_encode('0'));
        $this->graphQlMutation($negotiableQuoteQuery, [], '', $this->getHeaderMap());
    }

    /**
     * Testing that a quote for a different store on the same website is accessible
     *
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/company_customer_with_checkout_permissions.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/product_simple.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/negotiable_quote_by_customer_for_checkout.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/customer_address_for_customer.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/second_store.php
     * @magentoConfigFixture base_website btob/website_configuration/negotiablequote_active 1
     * @magentoConfigFixture base_website btob/website_configuration/company_active 1
     * @throws Exception
     */
    public function testSetBillingAddressForSecondStore(): void
    {
        $this->storeManager->setCurrentStore('secondstore');
        $headers = $this->getHeaderMap();
        $headers['Store'] = 'secondstore';

        $query = $this->getMutationWithCustomerAddressId('nq_customer_mask', base64_encode((string)2));
        $response = $this->graphQlMutation($query, [], '', $headers);

        // Assert the quote data is present and correct
        $this->assertNotEmpty($response['setNegotiableQuoteBillingAddress']);
        $this->assertArrayHasKey('quote', $response['setNegotiableQuoteBillingAddress']);
        $this->assertEquals('nq_customer_mask', $response['setNegotiableQuoteBillingAddress']['quote']['uid']);

        // Assert the billing address data is present and correct
        $this->assertArrayHasKey('billing_address', $response['setNegotiableQuoteBillingAddress']['quote']);
        $responseBillingAddress = $response['setNegotiableQuoteBillingAddress']['quote']['billing_address'];
        $this->assertResponseFields($responseBillingAddress, [
            'firstname' => 'John',
            'lastname' => 'Smith',
            'street' => ['Green str, 67'],
            'city' => 'CityM',
            'region' => ['code' => 'AL'],
            'postcode' => 75477,
            'country' => ['code' => 'US'],
            'telephone' => 3468676,
            'company' => 'CompanyName'
        ]);
    }

    /**
     * Testing that a quote for a different website is inaccessible
     *
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/company_customer_with_checkout_permissions.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/product_simple.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/negotiable_quote_by_customer_for_checkout.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/customer_address_for_customer.php
     * @magentoApiDataFixture Magento/NegotiableQuote/_files/second_website.php
     * @magentoConfigFixture secondwebsitestore_store customer/account_share/scope 0
     * @magentoConfigFixture secondwebsite_website btob/website_configuration/negotiablequote_active 1
     * @magentoConfigFixture secondwebsite_website btob/website_configuration/company_active 1
     * @throws Exception
     */
    public function testSetBillingAddressForInvalidWebsite(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Could not find a quote with the specified UID.');

        $this->storeManager->setCurrentStore('secondwebsitestore');
        $headers = $this->getHeaderMap();
        $headers['Store'] = 'secondwebsitestore';

        $query = $this->getMutationWithCustomerAddressId('nq_customer_mask', base64_encode((string)2));
        $this->graphQlMutation($query, [], '', $headers);
    }

    /**
     * Generates the GraphQl mutation to set the negotiable quote billing address based on an existing
     * customer address id.
     *
     * @param string $quoteId
     * @param string $customerAddressId
     * @return string
     */
    private function getMutationWithCustomerAddressId(string $quoteId, string $customerAddressId): string
    {
        return <<<MUTATION
mutation {
  setNegotiableQuoteBillingAddress(
    input: {
      quote_uid: "{$quoteId}"
      billing_address: {
        customer_address_uid: "{$customerAddressId}"
      }
    }
  ) {
    quote {
      uid
      billing_address {
        firstname
        lastname
        street
        city
        region {
          code
        }
        postcode
        country {
          code
        }
        telephone
        company
      }
    }
  }
}
MUTATION;
    }

    /**
     * Generates the GraphQl mutation to set the negotiable quote billing address based on an existing
     * customer address id and use the address for shipping
     *
     * @param string $quoteId
     * @param string $customerAddressId
     * @return string
     */
    private function getMutationWithCustomerAddressIdUseForShipping(string $quoteId, string $customerAddressId): string
    {
        return <<<MUTATION
mutation {
  setNegotiableQuoteBillingAddress(
    input: {
      quote_uid: "{$quoteId}"
      billing_address: {
        customer_address_uid: "{$customerAddressId}"
        use_for_shipping: true
      }
    }
  ) {
    quote {
      uid
      shipping_addresses {
        firstname
        lastname
        street
        city
        region {
          code
        }
        postcode
        country {
          code
        }
        telephone
        company
      }
      billing_address {
        firstname
        lastname
        street
        city
        region {
          code
        }
        postcode
        country {
          code
        }
        telephone
        company
      }
    }
  }
}
MUTATION;
    }

    /**
     * Generates the GraphQl mutation to set the negotiable quote billing address based on shipping address
     *
     * @param string $quoteId
     * @return string
     */
    private function getMutationWithNewAddressIdUseShipping(string $quoteId): string
    {
        return <<<MUTATION
mutation {
  setNegotiableQuoteBillingAddress(
    input: {
      quote_uid: "{$quoteId}"
      billing_address: {
        same_as_shipping: true
      }
    }
  ) {
    quote {
      uid
      billing_address {
        firstname
        lastname
        street
        city
        region {
          code
        }
        postcode
        country {
          code
        }
        telephone
        company
      }
    }
  }
}
MUTATION;
    }

    /**
     * Generates the GraphQl mutation to set the billing address based on new address input.
     *
     * @param string $quoteId
     * @return string
     */
    private function getMutationWithNewAddressInput(string $quoteId): string
    {
        return <<<MUTATION
mutation {
  setNegotiableQuoteBillingAddress(
    input: {
      quote_uid: "{$quoteId}"
      billing_address: {
        address: {
          firstname: "John",
          lastname: "Doe",
          country_code: "US",
          street: ["6161 West Centinela Ave."],
          city: "Culver City",
          region_id: 12,
          region: "CA",
          postcode: "90230",
          telephone: "5555555555",
          company: "Magento"
          save_in_address_book: false
        }
      }
    }
  ) {
    quote {
      uid
      billing_address {
        firstname
        lastname
        street
        city
        region {
          code
        }
        postcode
        country {
          code
        }
        telephone
        company
      }
    }
  }
}
MUTATION;
    }

    /**
     * Generates the GraphQl mutation to set the billing address based on both a customer address id
     * and new address input.
     *
     * @param string $quoteId
     * @return string
     */
    private function getMutationWithCustomerAddressIdAndNewAddressInput(
        string $quoteId,
        string $customerAddressId
    ): string {
        return <<<MUTATION
mutation {
  setNegotiableQuoteBillingAddress(
    input: {
      quote_uid: "{$quoteId}"
      billing_address: {
        customer_address_uid:  "{$customerAddressId}"
        address: {
          firstname: "John",
          lastname: "Doe",
          country_code: "US",
          street: ["6161 West Centinela Ave."],
          city: "Culver City",
          region_id: 12,
          region: "CA",
          postcode: "90230",
          telephone: "5555555555",
          company: "Magento"
          save_in_address_book: false
        }
      }
    }
  ) {
    quote {
      uid
      billing_address {
        firstname
        lastname
        street
        city
        region {
          code
        }
        postcode
        country {
          code
        }
        telephone
        company
      }
    }
  }
}
MUTATION;
    }

    /**
     * @param string $username
     * @param string $password
     * @return array
     * @throws AuthenticationException
     */
    private function getHeaderMap(
        string $username = 'customercompany22@example.com',
        string $password = 'password'
    ): array {
        $customerToken = $this->customerTokenService->createCustomerAccessToken($username, $password);
        return ['Authorization' => 'Bearer ' . $customerToken];
    }
}
