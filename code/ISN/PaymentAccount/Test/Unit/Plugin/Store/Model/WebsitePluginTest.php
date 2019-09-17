<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Test\Unit\Plugin\Store\Model;

/**
 * Class WebsitePluginTest.
 */
class WebsitePluginTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $searchCriteriaBuilder;

    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditLimitRepository;

    /**
     * @var \Magento\Framework\Message\ManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $messageManager;

    /**
     * @var \Magento\Framework\UrlInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $urlBuilder;

    /**
     * @var \ISN\PaymentAccount\Plugin\Store\Model\WebsitePlugin
     */
    private $websitePlugin;

    /**
     * Set up.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->searchCriteriaBuilder = $this->createMock(\Magento\Framework\Api\SearchCriteriaBuilder::class);
        $this->creditLimitRepository =
            $this->createMock(\ISN\PaymentAccount\Api\CreditLimitRepositoryInterface::class);
        $this->messageManager = $this->createMock(\Magento\Framework\Message\ManagerInterface::class);
        $this->urlBuilder = $this->createMock(\Magento\Framework\UrlInterface::class);

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->websitePlugin = $objectManager->getObject(
            \ISN\PaymentAccount\Plugin\Store\Model\WebsitePlugin::class,
            [
                'searchCriteriaBuilder' => $this->searchCriteriaBuilder,
                'creditLimitRepository' => $this->creditLimitRepository,
                'messageManager' => $this->messageManager,
                'urlBuilder' => $this->urlBuilder,
            ]
        );
    }

    /**
     * Test method for afterAfterDelete.
     *
     * @return void
     */
    public function testAfterAfterDelete()
    {
        $websiteName = 'Website Name';
        $currencyCode = 'USD';
        $url = '/admin/company/index';
        $website = $this->createMock(\Magento\Store\Model\Website::class);
        $website->expects($this->once())->method('getBaseCurrencyCode')->willReturn($currencyCode);
        $website->expects($this->once())->method('getName')->willReturn($websiteName);
        $this->searchCriteriaBuilder->expects($this->once())->method('addFilter')->with(
            \ISN\PaymentAccount\Api\Data\CreditLimitInterface::CURRENCY_CODE,
            $currencyCode
        )->willReturnSelf();
        $this->searchCriteriaBuilder->expects($this->once())->method('setPageSize')->with(0)->willReturnSelf();
        $searchCriteria = $this->createMock(\Magento\Framework\Api\SearchCriteriaInterface::class);
        $this->searchCriteriaBuilder->expects($this->once())->method('create')->willReturn($searchCriteria);
        $searchResults = $this->createMock(\Magento\Framework\Api\SearchResultsInterface::class);
        $this->creditLimitRepository->expects($this->once())
            ->method('getList')->with($searchCriteria)->willReturn($searchResults);
        $searchResults->expects($this->once())->method('getTotalCount')->willReturn(1);
        $this->urlBuilder->expects($this->once())->method('getUrl')->with('company/index')->willReturn($url);
        $this->messageManager->expects($this->once())->method('addComplexWarningMessage')->with(
            'baseCurrencyChangeWarning',
            [
                'websiteName' => $websiteName,
                'currencyCode' => $currencyCode,
                'url' => $url,
            ]
        )->willReturnSelf();
        $this->assertSame($website, $this->websitePlugin->afterAfterDelete($website));
    }
}
