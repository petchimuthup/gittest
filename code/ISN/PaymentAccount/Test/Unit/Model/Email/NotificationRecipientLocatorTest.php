<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Test\Unit\Model\Email;

/**
 * Class NotificationRecipientLocatorTest.
 */
class NotificationRecipientLocatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditLimitRepository;

    /**
     * @var \Magento\Company\Api\CompanyManagementInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $companyManagement;

    /**
     * @var \ISN\PaymentAccount\Model\Email\NotificationRecipientLocator
     */
    private $model;

    /**
     * Set up.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->creditLimitRepository = $this->createMock(
            \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface::class
        );
        $this->companyManagement = $this->createMock(
            \Magento\Company\Api\CompanyManagementInterface::class
        );
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->model = $objectManager->getObject(
            \ISN\PaymentAccount\Model\Email\NotificationRecipientLocator::class,
            [
                'creditLimitRepository' => $this->creditLimitRepository,
                'companyManagement' => $this->companyManagement,
            ]
        );
    }

    /**
     * Test getByRecord method.
     *
     * @return void
     */
    public function testGetByRecord()
    {
        $accountPaymentId = 1;
        $companyId = 1;
        $history = $this->createMock(
            \ISN\PaymentAccount\Model\HistoryInterface::class
        );
        $creditLimit = $this->createMock(
            \ISN\PaymentAccount\Api\Data\CreditLimitInterface::class
        );
        $customer = $this->createMock(
            \Magento\Customer\Api\Data\CustomerInterface::class
        );
        $history->expects($this->once())->method('getAccountPaymentId')->willReturn($accountPaymentId);
        $this->creditLimitRepository->expects($this->once())
            ->method('get')
            ->with($accountPaymentId)
            ->willReturn($creditLimit);
        $creditLimit->expects($this->once())->method('getCompanyId')->willReturn($companyId);
        $this->companyManagement->expects($this->once())
            ->method('getAdminByCompanyId')
            ->with($companyId)
            ->willReturn($customer);

        $this->assertEquals($customer, $this->model->getByRecord($history));
    }
}
