<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Test\Unit\Observer;

use ISN\PaymentAccount\Api\Data\CreditLimitInterface;

/**
 * Unit tests for ISN\PaymentAccount\Observer\AfterCompanySaveObserver class.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AfterCompanySaveObserverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \ISN\PaymentAccount\Observer\AfterCompanySaveObserver
     */
    private $afterCompanySaveObserver;

    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditLimitRepository;

    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitManagementInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditLimitManagement;

    /**
     * @var \ISN\PaymentAccount\Model\CreditLimitHistory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditLimitHistory;

    /**
     * @var \ISN\PaymentAccount\Model\CreditCurrency|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditCurrency;

    /**
     * @var \Magento\Framework\Locale\ResolverInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $localeResolver;

    /**
     * @var \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditLimitFactory;

    /**
     * @var \Magento\Framework\Event\Observer|\PHPUnit_Framework_MockObject_MockObject
     */
    private $observer;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->creditLimitRepository = $this
            ->getMockBuilder(\ISN\PaymentAccount\Api\CreditLimitRepositoryInterface::class)
            ->disableOriginalConstructor()->getMockForAbstractClass();
        $this->creditLimitManagement = $this
            ->getMockBuilder(\ISN\PaymentAccount\Api\CreditLimitManagementInterface::class)
            ->disableOriginalConstructor()->getMockForAbstractClass();
        $this->creditLimitHistory = $this->getMockBuilder(\ISN\PaymentAccount\Model\CreditLimitHistory::class)
            ->disableOriginalConstructor()->getMock();
        $this->observer = $this->getMockBuilder(\Magento\Framework\Event\Observer::class)
            ->setMethods(['getRequest', 'getCompany'])
            ->disableOriginalConstructor()->getMock();
        $this->localeResolver = $this->getMockBuilder(\Magento\Framework\Locale\ResolverInterface::class)
            ->disableOriginalConstructor()->getMockForAbstractClass();
        $this->creditCurrency = $this->getMockBuilder(\ISN\PaymentAccount\Model\CreditCurrency::class)
            ->disableOriginalConstructor()->getMock();
        $this->creditLimitFactory = $this
            ->getMockBuilder(\ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()->getMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->afterCompanySaveObserver = $objectManager->getObject(
            \ISN\PaymentAccount\Observer\AfterCompanySaveObserver::class,
            [
                'creditLimitHistory' => $this->creditLimitHistory,
                'creditLimitRepository' => $this->creditLimitRepository,
                'creditLimitManagement' => $this->creditLimitManagement,
                'localeResolver' => $this->localeResolver,
                'creditCurrency' => $this->creditCurrency,
                'creditLimitFactory' => $this->creditLimitFactory,
            ]
        );
    }

    /**
     * Test execute method.
     *
     * @param int|null $accountPaymentLimitValue
     * @param string $code
     * @param float|null $rate
     * @param \PHPUnit\Framework\MockObject\Matcher\InvokedCount $invokesMatched
     * @param \PHPUnit\Framework\MockObject\Matcher\InvokedCount $saveInvoked
     * @dataProvider executeDataProvider
     * @return void
     */
    public function testExecute(
        $accountPaymentLimitValue,
        $code,
        $rate,
        \PHPUnit\Framework\MockObject\Matcher\InvokedCount $invokesMatched,
        \PHPUnit\Framework\MockObject\Matcher\InvokedCount $saveInvoked
    ) {
        $creditLimitId = 1;
        $accountPaymentCurrencyCode ='USD';
        $params = [
            'company_credit' => [
                CreditLimitInterface::EXCEED_LIMIT => 1,
                CreditLimitInterface::CURRENCY_CODE => $accountPaymentCurrencyCode,
                CreditLimitInterface::CREDIT_LIMIT => $accountPaymentLimitValue,
                'credit_comment' => 'test',
                'currency_rate' => $rate,
            ]
        ];
        $request = $this->getMockBuilder(\Magento\Framework\App\RequestInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getParams'])
            ->getMockForAbstractClass();
        $company = $this->getMockBuilder(\Magento\Company\Api\Data\CompanyInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $creditLimit = $this->getMockBuilder(\ISN\PaymentAccount\Model\CreditLimit::class)
            ->disableOriginalConstructor()
            ->getMock();
        $company->expects($this->atLeastOnce())->method('getId')->willReturn(1);
        $this->observer->expects($this->once())->method('getRequest')->willReturn($request);
        $this->observer->expects($this->once())->method('getCompany')->willReturn($company);
        $request->expects($this->once())->method('getParams')->willReturn($params);
        $this->creditLimitManagement->expects($this->once())
            ->method('getCreditByCompanyId')->with(1)->willReturn($creditLimit);
        $creditLimit->expects($this->once())->method('getCurrencyCode')->willReturn($code);
        $creditLimit->expects($this->atLeastOnce())->method('getId')->willReturn($creditLimitId);
        $creditData = $params['company_credit'];
        $extraCreditData = [
            CreditLimitInterface::CREDIT_ID => $creditLimitId,
            CreditLimitInterface::COMPANY_ID => 1
        ];
        array_merge($creditData, $extraCreditData);

        $creditLimit->expects($this->once())->method('setData')->with();
        $creditLimit->expects($this->once())->method('setExceedLimit')->with(true);

        if ($accountPaymentCurrencyCode === $code) {
            $this->creditLimitRepository->expects($saveInvoked)->method('save')->with($creditLimit);
        }
        $creditLimit->expects($this->once())->method('setCreditLimit')->with((int) $accountPaymentLimitValue);

        $this->localeResolver->expects($invokesMatched)->method('getLocale')->willReturn('en_US');

        $this->afterCompanySaveObserver->execute($this->observer);
    }

    /**
     * Data provide for execute method..
     *
     * @return array
     */
    public function executeDataProvider()
    {
        return [
            [100, 'USD', 1, $this->once(), $this->once()],
            [null, 'USD', null, $this->never(), $this->once()],
            [null, 'EUR', 1.12, $this->never(), $this->never()]
        ];
    }

    /**
     * Test for execute method with Localized exception.
     *
     * @return void
     * @expectedException \Magento\Framework\Exception\LocalizedException
     * @expectedExceptionMessage Please enter a valid EUR/USD currency rate.
     */
    public function testExecuteWithException()
    {
        $params = [
            'company_credit' => [
                CreditLimitInterface::CURRENCY_CODE => 'USD',
                'credit_comment' => 'test',
                'currency_rate' => -1,
            ]
        ];
        $request = $this->getMockBuilder(\Magento\Framework\App\RequestInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getParams'])
            ->getMockForAbstractClass();
        $company = $this->getMockBuilder(\Magento\Company\Api\Data\CompanyInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $creditLimit = $this->getMockBuilder(\ISN\PaymentAccount\Model\CreditLimit::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->observer->expects($this->once())->method('getRequest')->willReturn($request);
        $request->expects($this->once())->method('getParams')->willReturn($params);
        $this->observer->expects($this->once())->method('getCompany')->willReturn($company);
        $company->expects($this->atLeastOnce())->method('getId')->willReturn(1);
        $this->creditLimitManagement->expects($this->once())
            ->method('getCreditByCompanyId')
            ->with(1)
            ->willReturn($creditLimit);
        $creditLimit->expects($this->once())->method('getCurrencyCode')->willReturn('EUR');

        $this->afterCompanySaveObserver->execute($this->observer);
    }

    /**
     * Test for execute method with NoSuchEntity exception.
     *
     * @return void
     */
    public function testExecuteWithNoSuchEntityException()
    {
        $creditLimitId = 1;
        $accountPaymentCurrencyCode ='USD';
        $params = [
            'company_credit' => [
                CreditLimitInterface::EXCEED_LIMIT => 1,
                CreditLimitInterface::CURRENCY_CODE => $accountPaymentCurrencyCode,
                CreditLimitInterface::CREDIT_LIMIT => 100,
                'credit_comment' => 'test',
                'currency_rate' => 1,
            ]
        ];
        $exception = new \Magento\Framework\Exception\NoSuchEntityException();
        $request = $this->getMockBuilder(\Magento\Framework\App\RequestInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getParams'])
            ->getMockForAbstractClass();
        $company = $this->getMockBuilder(\Magento\Company\Api\Data\CompanyInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $creditLimit = $this->getMockBuilder(\ISN\PaymentAccount\Model\CreditLimit::class)
            ->disableOriginalConstructor()
            ->getMock();
        $company->expects($this->atLeastOnce())->method('getId')->willReturn(1);
        $this->observer->expects($this->once())->method('getRequest')->willReturn($request);
        $this->observer->expects($this->once())->method('getCompany')->willReturn($company);
        $request->expects($this->once())->method('getParams')->willReturn($params);
        $this->creditLimitManagement->expects($this->once())
            ->method('getCreditByCompanyId')->with(1)->willThrowException($exception);
        $this->creditLimitFactory->expects($this->once())->method('create')->willReturn($creditLimit);
        $creditLimit->expects($this->once())->method('setCompanyId')->with(1)->willReturnSelf();
        $creditLimit->expects($this->once())->method('getCurrencyCode')->willReturn('USD');
        $creditLimit->expects($this->atLeastOnce())->method('getId')->willReturn($creditLimitId);
        $creditData = $params['company_credit'];
        $extraCreditData = [
            CreditLimitInterface::CREDIT_ID => $creditLimitId,
            CreditLimitInterface::COMPANY_ID => 1
        ];
        array_merge($creditData, $extraCreditData);

        $creditLimit->expects($this->once())->method('setData')->with();
        $creditLimit->expects($this->once())->method('setExceedLimit')->with(true);
        $this->creditLimitRepository->expects($this->once())->method('save')->with($creditLimit);
        $creditLimit->expects($this->once())->method('setCreditLimit')->with(100);

        $this->localeResolver->expects($this->once())->method('getLocale')->willReturn('en_US');

        $this->afterCompanySaveObserver->execute($this->observer);
    }
}
