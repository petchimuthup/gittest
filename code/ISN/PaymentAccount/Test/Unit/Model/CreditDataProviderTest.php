<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Test\Unit\Model;

/**
 * Class CreditDataProviderTest.
 */
class CreditDataProviderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitManagementInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditLimitManagement;

    /**
     * @var \ISN\PaymentAccount\Model\CreditDataFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditDataFactory;

    /**
     * @var \ISN\PaymentAccount\Model\CreditDataProvider
     */
    private $creditDataProvider;

    /**
     * Set up.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->creditLimitManagement = $this->createMock(
            \ISN\PaymentAccount\Api\CreditLimitManagementInterface::class
        );
        $this->creditDataFactory = $this->createPartialMock(
            \ISN\PaymentAccount\Model\CreditDataFactory::class,
            ['create']
        );

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->creditDataProvider = $objectManager->getObject(
            \ISN\PaymentAccount\Model\CreditDataProvider::class,
            [
                'creditLimitManagement' => $this->creditLimitManagement,
                'creditDataFactory' => $this->creditDataFactory,
            ]
        );
    }

    /**
     * Test for get method.
     *
     * @return void
     */
    public function testGet()
    {
        $companyId = 1;
        $creditLimit = $this->createMock(\ISN\PaymentAccount\Api\Data\CreditLimitInterface::class);
        $this->creditLimitManagement->expects($this->once())
            ->method('getCreditByCompanyId')->with($companyId)->willReturn($creditLimit);
        $creditData = $this->createMock(\ISN\PaymentAccount\Api\Data\CreditDataInterface::class);
        $this->creditDataFactory->expects($this->once())
            ->method('create')->with(['credit' => $creditLimit])->willReturn($creditData);
        $this->assertEquals($creditData, $this->creditDataProvider->get($companyId));
    }
}
