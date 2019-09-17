<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Test\Unit\Plugin\Company\Model;

/**
 * Unit test for AccountPaymentCreatePlugin.
 */
class AccountPaymentCreatePluginTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditLimitFactory;

    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditLimitRepository;

    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitManagementInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditLimitManagement;

    /**
     * @var \Magento\Store\Api\WebsiteRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $websiteRepository;

    /**
     * @var \Magento\Framework\App\RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    /**
     * @var \ISN\PaymentAccount\Plugin\Company\Model\AccountPaymentCreatePlugin
     */
    private $accountPaymentCreatePlugin;

    /**
     * Set up.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->creditLimitFactory = $this
            ->getMockBuilder(\ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()->getMock();
        $this->creditLimitRepository = $this
            ->getMockBuilder(\ISN\PaymentAccount\Api\CreditLimitRepositoryInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->creditLimitManagement = $this
            ->getMockBuilder(\ISN\PaymentAccount\Api\CreditLimitManagementInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->websiteRepository = $this->getMockBuilder(\Magento\Store\Api\WebsiteRepositoryInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->request = $this->getMockBuilder(\Magento\Framework\App\RequestInterface::class)
            ->disableOriginalConstructor()->getMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->accountPaymentCreatePlugin = $objectManager->getObject(
            \ISN\PaymentAccount\Plugin\Company\Model\AccountPaymentCreatePlugin::class,
            [
                'creditLimitFactory' => $this->creditLimitFactory,
                'creditLimitRepository' => $this->creditLimitRepository,
                'creditLimitManagement' => $this->creditLimitManagement,
                'websiteRepository' => $this->websiteRepository,
                'request' => $this->request,
            ]
        );
    }

    /**
     * Test for afterSave method.
     *
     * @return void
     */
    public function testAfterSave()
    {
        $companyId = 1;
        $company = $this->getMockBuilder(\Magento\Company\Api\Data\CompanyInterface::class)
            ->disableOriginalConstructor()->getMock();
        $company->expects($this->once())->method('getId')->willReturn($companyId);
        $creditLimit = $this->getMockBuilder(\ISN\PaymentAccount\Api\Data\CreditLimitInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->creditLimitManagement->expects($this->once())
            ->method('getCreditByCompanyId')->with($companyId)->willReturn($creditLimit);
        $creditLimit->expects($this->once())->method('getId')->willReturn(2);
        $companySave = $this->getMockBuilder(\Magento\Company\Model\Company\Save::class)
            ->disableOriginalConstructor()->getMock();
        $this->assertEquals($company, $this->accountPaymentCreatePlugin->afterSave($companySave, $company));
    }

    /**
     * Test for afterSave method without credit limit.
     *
     * @return void
     */
    public function testAfterSaveWithoutCreditLimit()
    {
        $companyId = 1;
        $currencyCode = 'USD';
        $company = $this->getMockBuilder(\Magento\Company\Api\Data\CompanyInterface::class)
            ->disableOriginalConstructor()->getMock();
        $company->expects($this->atLeastOnce())->method('getId')->willReturn($companyId);
        $this->creditLimitManagement->expects($this->once())->method('getCreditByCompanyId')->with($companyId)
            ->willThrowException(new \Magento\Framework\Exception\NoSuchEntityException());
        $creditLimit = $this->getMockBuilder(\ISN\PaymentAccount\Api\Data\CreditLimitInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->creditLimitFactory->expects($this->once())->method('create')->willReturn($creditLimit);
        $creditLimit->expects($this->atLeastOnce())->method('setCompanyId')->with($companyId)->willReturnSelf();
        $creditLimit->expects($this->once())->method('getId')->willReturn(null);
        $this->request->expects($this->once())->method('getParam')->with('company_credit')->willReturn(null);
        $website = $this->getMockBuilder(\Magento\Store\Api\Data\WebsiteInterface::class)
            ->setMethods(['getBaseCurrencyCode'])
            ->disableOriginalConstructor()->getMockForAbstractClass();
        $this->websiteRepository->expects($this->once())->method('getDefault')->willReturn($website);
        $website->expects($this->once())->method('getBaseCurrencyCode')->willReturn($currencyCode);
        $creditLimit->expects($this->once())->method('setCurrencyCode')->with($currencyCode)->willReturnSelf();
        $this->creditLimitRepository->expects($this->once())
            ->method('save')->with($creditLimit)->willReturn($creditLimit);
        $companySave = $this->getMockBuilder(\Magento\Company\Model\Company\Save::class)
            ->disableOriginalConstructor()->getMock();
        $this->assertEquals($company, $this->accountPaymentCreatePlugin->afterSave($companySave, $company));
    }
}
