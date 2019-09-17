<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Test\Unit\Model;

/**
 * Unit test for CreditLimitManagement model.
 */
class CreditLimitManagementTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \ISN\PaymentAccount\Model\CreditLimitFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditLimitFactory;

    /**
     * @var \ISN\PaymentAccount\Model\ResourceModel\CreditLimit|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditLimitResource;

    /**
     * @var \ISN\PaymentAccount\Model\CreditLimitManagement
     */
    private $creditLimitManagement;

    /**
     * Set up.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->creditLimitFactory = $this->getMockBuilder(\ISN\PaymentAccount\Model\CreditLimitFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()->getMock();
        $this->creditLimitResource = $this
            ->getMockBuilder(\ISN\PaymentAccount\Model\ResourceModel\CreditLimit::class)
            ->disableOriginalConstructor()->getMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->creditLimitManagement = $objectManager->getObject(
            \ISN\PaymentAccount\Model\CreditLimitManagement::class,
            [
                'creditLimitFactory' => $this->creditLimitFactory,
                'creditLimitResource' => $this->creditLimitResource,
            ]
        );
    }

    /**
     * Test for method getCreditByCompanyId.
     *
     * @return void
     */
    public function testGetCreditByCompanyId()
    {
        $creditLimitId = 1;
        $companyId = 2;
        $creditLimit = $this->getMockBuilder(\ISN\PaymentAccount\Model\CreditLimit::class)
            ->disableOriginalConstructor()->getMock();
        $this->creditLimitFactory->expects($this->once())->method('create')->willReturn($creditLimit);
        $this->creditLimitResource->expects($this->once())->method('load')
            ->with($creditLimit, $companyId, \ISN\PaymentAccount\Api\Data\CreditLimitInterface::COMPANY_ID)
            ->willReturnSelf();
        $creditLimit->expects($this->once())->method('getId')->willReturn($creditLimitId);
        $this->assertEquals($creditLimit, $this->creditLimitManagement->getCreditByCompanyId($companyId));
    }

    /**
     * Test for method getCreditByCompanyId with exception.
     *
     * @return void
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage Requested company is not found. Row ID: CompanyID = 2.
     */
    public function testGetCreditByCompanyIdWithException()
    {
        $companyId = 2;
        $creditLimit = $this->getMockBuilder(\ISN\PaymentAccount\Model\CreditLimit::class)
            ->disableOriginalConstructor()->getMock();
        $this->creditLimitFactory->expects($this->once())->method('create')->willReturn($creditLimit);
        $this->creditLimitResource->expects($this->once())->method('load')
            ->with($creditLimit, $companyId, \ISN\PaymentAccount\Api\Data\CreditLimitInterface::COMPANY_ID)
            ->willReturnSelf();
        $creditLimit->expects($this->once())->method('getId')->willReturn(null);
        $this->creditLimitManagement->getCreditByCompanyId($companyId);
    }
}
