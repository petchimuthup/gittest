<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Test\Unit\Block\Company;

/**
 * Class CreditBalanceTest.
 */
class CreditBalanceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \ISN\PaymentAccount\Model\CreditDetails\CustomerProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    private $customerProvider;

    /**
     * @var \ISN\PaymentAccount\Api\CreditDataProviderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditDataProvider;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $priceFormatter;

    /**
     * @var \ISN\PaymentAccount\Model\WebsiteCurrency|\PHPUnit_Framework_MockObject_MockObject
     */
    private $websiteCurrency;

    /**
     * @var \ISN\PaymentAccount\Block\Company\CreditBalance
     */
    private $creditBalance;

    /**
     * Set up.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->customerProvider = $this->createMock(
            \ISN\PaymentAccount\Model\CreditDetails\CustomerProvider::class
        );
        $this->creditDataProvider = $this->createMock(
            \ISN\PaymentAccount\Api\CreditDataProviderInterface::class
        );
        $this->priceFormatter = $this->createMock(
            \Magento\Framework\Pricing\PriceCurrencyInterface::class
        );
        $this->websiteCurrency = $this->createMock(
            \ISN\PaymentAccount\Model\WebsiteCurrency::class
        );

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->creditBalance = $objectManager->getObject(
            \ISN\PaymentAccount\Block\Company\CreditBalance::class,
            [
                'customerProvider' => $this->customerProvider,
                'creditDataProvider' => $this->creditDataProvider,
                'priceFormatter' => $this->priceFormatter,
                'websiteCurrency' => $this->websiteCurrency,
            ]
        );
    }

    /**
     * Test for isOutstandingBalanceNegative method.
     *
     * @return void
     */
    public function testIsOutstandingBalanceNegative()
    {
        $companyId = 1;
        $creditData = $this->createMock(
            \ISN\PaymentAccount\Api\Data\CreditDataInterface::class
        );
        $this->customerProvider->expects($this->exactly(2))->method('getCurrentUserCredit')->willReturn($creditData);
        $creditData->expects($this->once())->method('getCompanyId')->willReturn($companyId);
        $this->creditDataProvider->expects($this->once())->method('get')->with($companyId)->willReturn($creditData);
        $creditData->expects($this->once())->method('getBalance')->willReturn(-30);
        $this->assertTrue($this->creditBalance->isOutstandingBalanceNegative());
    }

    /**
     * Test for getOutstandingBalance method.
     *
     * @return void
     */
    public function testGetOutstandingBalance()
    {
        $this->assertEquals($this->mockCreditData('getBalance'), $this->creditBalance->getOutstandingBalance());
    }

    /**
     * Test for getAvailableCredit method.
     *
     * @return void
     */
    public function testGetAvailableCredit()
    {
        $this->assertEquals($this->mockCreditData('getAvailableLimit'), $this->creditBalance->getAvailableCredit());
    }

    /**
     * Test for getCreditLimit method.
     *
     * @return void
     */
    public function testGetCreditLimit()
    {
        $this->assertEquals($this->mockCreditData('getCreditLimit'), $this->creditBalance->getCreditLimit());
    }

    /**
     * Mock credit data.
     *
     * @param string $method
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function mockCreditData($method)
    {
        $companyId = 1;
        $amount = 100;
        $creditCurrency = 'USD';
        $expectedResult = sprintf('$%.2f', $amount);
        $creditData = $this->createMock(
            \ISN\PaymentAccount\Api\Data\CreditDataInterface::class
        );
        $this->customerProvider->expects($this->exactly(2))->method('getCurrentUserCredit')->willReturn($creditData);
        $creditData->expects($this->once())->method('getCompanyId')->willReturn($companyId);
        $this->creditDataProvider->expects($this->once())->method('get')->with($companyId)->willReturn($creditData);
        $creditData->expects($this->once())->method($method)->willReturn($amount);
        $creditData->expects($this->once())->method('getCurrencyCode')->willReturn($creditCurrency);
        $currency = $this->createMock(\Magento\Directory\Model\Currency::class);
        $this->websiteCurrency->expects($this->once())
            ->method('getCurrencyByCode')->with($creditCurrency)->willReturn($currency);
        $this->priceFormatter->expects($this->once())->method('format')->with(
            $amount,
            false,
            \Magento\Framework\Pricing\PriceCurrencyInterface::DEFAULT_PRECISION,
            null,
            $currency
        )->willReturn($expectedResult);
        return $expectedResult;
    }
}
