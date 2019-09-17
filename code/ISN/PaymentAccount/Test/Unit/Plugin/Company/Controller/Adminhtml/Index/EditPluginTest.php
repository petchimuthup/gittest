<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Test\Unit\Plugin\Company\Controller\Adminhtml\Index;

/**
 * Unit test for ISN\PaymentAccount\Plugin\Company\Controller\Adminhtml\Index\EditPlugin class.
 */
class EditPluginTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\App\RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitManagementInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditLimitManagement;

    /**
     * @var \ISN\PaymentAccount\Model\WebsiteCurrency|\PHPUnit_Framework_MockObject_MockObject
     */
    private $websiteCurrency;

    /**
     * @var \Magento\Framework\Message\ManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $messageManager;

    /**
     * @var \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditLimitFactory;

    /**
     * @var \ISN\PaymentAccount\Plugin\Company\Controller\Adminhtml\Index\EditPlugin
     */
    private $editPlugin;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->request = $this->getMockBuilder(\Magento\Framework\App\RequestInterface::class)
            ->disableOriginalConstructor()->getMockForAbstractClass();
        $this->creditLimitManagement = $this->getMockBuilder(
            \ISN\PaymentAccount\Api\CreditLimitManagementInterface::class
        )
            ->disableOriginalConstructor()->getMockForAbstractClass();
        $this->websiteCurrency = $this->getMockBuilder(\ISN\PaymentAccount\Model\WebsiteCurrency::class)
            ->disableOriginalConstructor()->getMock();
        $this->messageManager = $this->getMockBuilder(\Magento\Framework\Message\ManagerInterface::class)
            ->disableOriginalConstructor()->getMockForAbstractClass();
        $this->creditLimitFactory = $this
            ->getMockBuilder(\ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()->getMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->editPlugin = $objectManager->getObject(
            \ISN\PaymentAccount\Plugin\Company\Controller\Adminhtml\Index\EditPlugin::class,
            [
                'request' => $this->request,
                'creditLimitManagement' => $this->creditLimitManagement,
                'websiteCurrency' => $this->websiteCurrency,
                'messageManager' => $this->messageManager,
                'creditLimitFactory' => $this->creditLimitFactory,
            ]
        );
    }

    /**
     * Test beforeExecute method.
     *
     * @return void
     */
    public function testBeforeExecute()
    {
        $companyId = 1;
        $creditCurrencyCode = 'USD';
        $this->request->expects(static::once())->method('getParam')->with('id')->willReturn($companyId);
        $creditLimit = $this->getMockBuilder(\ISN\PaymentAccount\Api\Data\CreditLimitInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $creditLimit->expects(static::once())->method('getCurrencyCode')->willReturn($creditCurrencyCode);
        $this->creditLimitManagement->expects(static::once())->method('getCreditByCompanyId')
            ->with($companyId)
            ->willReturn($creditLimit);
        $this->websiteCurrency->expects(static::once())
            ->method('isCreditCurrencyEnabled')
            ->with($creditCurrencyCode)
            ->willReturn(false);
        $this->messageManager->expects(self::atLeastOnce())->method('addNoticeMessage');
        $subject = $this->getMockBuilder(\Magento\Company\Controller\Adminhtml\Index\Edit::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertEquals(
            $this->editPlugin->beforeExecute($subject),
            []
        );
    }

    /**
     * Test beforeExecute method with exception.
     *
     * @return void
     */
    public function testBeforeExecuteWithException()
    {
        $companyId = 1;
        $creditCurrencyCode = 'USD';
        $exception = new \Magento\Framework\Exception\NoSuchEntityException();
        $this->request->expects(static::once())->method('getParam')->with('id')->willReturn($companyId);
        $creditLimit = $this->getMockBuilder(\ISN\PaymentAccount\Api\Data\CreditLimitInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->creditLimitManagement->expects($this->once())
            ->method('getCreditByCompanyId')->with($companyId)->willThrowException($exception);
        $this->creditLimitFactory->expects($this->once())->method('create')->willReturn($creditLimit);
        $creditLimit->expects($this->once())->method('setCompanyId')->with($companyId)->willReturnSelf();
        $creditLimit->expects(static::once())->method('getCurrencyCode')->willReturn($creditCurrencyCode);
        $this->websiteCurrency->expects(static::once())
            ->method('isCreditCurrencyEnabled')
            ->with($creditCurrencyCode)
            ->willReturn(false);
        $this->messageManager->expects(self::atLeastOnce())->method('addNoticeMessage');
        $subject = $this->getMockBuilder(\Magento\Company\Controller\Adminhtml\Index\Edit::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertEquals(
            $this->editPlugin->beforeExecute($subject),
            []
        );
    }
}
