<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Test\Unit\Model\CreditDetails;

/**
 * Class CustomerProviderTest.
 */
class CustomerProviderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \ISN\PaymentAccount\Api\CreditDataProviderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditDataProvider;

    /**
     * @var \Magento\Authorization\Model\UserContextInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $userContext;

    /**
     * @var \Magento\Company\Api\CompanyManagementInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $companyManagement;

    /**
     * @var \ISN\PaymentAccount\Model\CreditDetails\CustomerProvider
     */
    private $customerProvider;

    /**
     * Set up.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->creditDataProvider = $this->createMock(
            \ISN\PaymentAccount\Api\CreditDataProviderInterface::class
        );
        $this->userContext = $this->createMock(
            \Magento\Authorization\Model\UserContextInterface::class
        );
        $this->companyManagement = $this->createMock(
            \Magento\Company\Api\CompanyManagementInterface::class
        );

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->customerProvider = $objectManager->getObject(
            \ISN\PaymentAccount\Model\CreditDetails\CustomerProvider::class,
            [
                'creditDataProvider' => $this->creditDataProvider,
                'userContext' => $this->userContext,
                'companyManagement' => $this->companyManagement,
            ]
        );
    }

    /**
     * Test for method getCurrentUserCredit.
     *
     * @return void
     */
    public function testGetCurrentUserCredit()
    {
        $userId = 1;
        $companyId = 2;
        $this->userContext->expects($this->exactly(2))->method('getUserId')->willReturn($userId);
        $this->userContext->expects($this->once())
            ->method('getUserType')->willReturn(\Magento\Authorization\Model\UserContextInterface::USER_TYPE_CUSTOMER);
        $company = $this->createMock(\Magento\Company\Api\Data\CompanyInterface::class);
        $this->companyManagement->expects($this->once())
            ->method('getByCustomerId')->with($userId)->willReturn($company);
        $credit = $this->createMock(\ISN\PaymentAccount\Api\Data\CreditDataInterface::class);
        $company->expects($this->once())->method('getId')->willReturn($companyId);
        $this->creditDataProvider->expects($this->once())->method('get')->with($companyId)->willReturn($credit);
        $this->assertEquals($credit, $this->customerProvider->getCurrentUserCredit());
    }
}
