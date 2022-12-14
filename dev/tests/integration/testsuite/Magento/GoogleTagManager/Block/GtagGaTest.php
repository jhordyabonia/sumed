<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\GoogleTagManager\Block;

use Magento\Cms\Controller\Page\View;
use PHPUnit\Framework\TestCase;
use Magento\Cms\Model\GetPageByIdentifier;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use Magento\Cms\Helper\Page as PageHelper;

/**
 * Integration tests for Google Tag Manager block
 *
 * @magentoAppArea frontend
 */
class GtagGaTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
    }

    /**
     * Test that that page contains Google Tag Manager code into the correct position
     *
     * @magentoDataFixture Magento/Cms/_files/pages.php
     * @magentoConfigFixture current_store google/gtag/analytics4/active 1
     * @magentoConfigFixture current_store google/gtag/analytics4/type tag_manager
     * @magentoConfigFixture current_store google/gtag/analytics4/container_id container_id
     */
    public function testCheckTagManagerBlockPosition()
    {
        $expectedTemplate = 'Magento_GoogleTagManager::gtag_gtm.phtml';
        $pageHelper = $this->objectManager->get(PageHelper::class);
        $pageViewAction = $this->objectManager->get(View::class);
        $cmsPage = $this->objectManager->get(GetPageByIdentifier::class)->execute('page100', 0);
        $pageId = $cmsPage->getId();
        /** @var \Magento\Framework\View\Result\Page $pageResult */
        $pageResult = $pageHelper->prepareResultPage($pageViewAction, $pageId);
        $layoutUpdate = $pageResult->getLayout()->getUpdate()->asSimplexml();
        $selector = "body/referenceContainer[@name='after.body.start']/block[@template='" . $expectedTemplate . "']";
        $gtmBlocksCount = count($layoutUpdate->xpath($selector));

        $this->assertSame(1, $gtmBlocksCount);
    }
}
