<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Test\Unit\Model\Email;

/**
 * Class SenderTest.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SenderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $transportBuilder;

    /**
     * @var \Psr\Log\LoggerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $logger;

    /**
     * @var \ISN\PaymentAccount\Model\Email\AccountPaymentDataFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $accountPaymentDataFactory;

    /**
     * @var \ISN\PaymentAccount\Model\Config\EmailTemplate|\PHPUnit_Framework_MockObject_MockObject
     */
    private $emailTemplateConfig;

    /**
     * @var \ISN\PaymentAccount\Model\Email\NotificationRecipientLocator|\PHPUnit_Framework_MockObject_MockObject
     */
    private $notificationRecipient;

    /**
     * @var \ISN\PaymentAccount\Model\Email\Sender
     */
    private $model;

    /**
     * Set up.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->transportBuilder = $this->createPartialMock(
            \Magento\Framework\Mail\Template\TransportBuilder::class,
            [
                'setFrom',
                'addTo',
                'getTransport',
                'addBcc',
                'setTemplateIdentifier',
                'setTemplateVars',
                'setTemplateOptions'
            ]
        );
        $this->logger = $this->createMock(
            \Psr\Log\LoggerInterface::class
        );
        $this->accountPaymentDataFactory = $this->createMock(
            \ISN\PaymentAccount\Model\Email\AccountPaymentDataFactory::class
        );
        $this->emailTemplateConfig = $this->createMock(
            \ISN\PaymentAccount\Model\Config\EmailTemplate::class
        );
        $this->notificationRecipient = $this->createMock(
            \ISN\PaymentAccount\Model\Email\NotificationRecipientLocator::class
        );

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->model = $objectManager->getObject(
            \ISN\PaymentAccount\Model\Email\Sender::class,
            [
                'transportBuilder' => $this->transportBuilder,
                'logger' => $this->logger,
                'accountPaymentDataFactory' => $this->accountPaymentDataFactory,
                'emailTemplateConfig' => $this->emailTemplateConfig,
                'notificationRecipient' => $this->notificationRecipient,
            ]
        );
    }

    /**
     * Test sendAccountPaymentChangedNotificationEmail method.
     *
     * @return void
     */
    public function testSendAccountPaymentChangedNotificationEmail()
    {
        $storeId = 1;
        $templateId = 'company_email_credit_allocated_email_template';
        $copyTo = 'info@example.com';
        $accountPaymentData = new \Magento\Framework\DataObject();
        $history = $this->createMock(
            \ISN\PaymentAccount\Model\HistoryInterface::class
        );
        $customer = $this->createMock(
            \Magento\Customer\Api\Data\CustomerInterface::class
        );
        $transport = $this->createMock(
            \Magento\Framework\Mail\TransportInterface::class
        );
        $this->notificationRecipient->expects($this->once())
            ->method('getByRecord')
            ->with($history)
            ->willReturn($customer);
        $customer->expects($this->once())->method('getStoreId')->willReturn(null);
        $this->emailTemplateConfig->expects($this->once())
            ->method('getDefaultStoreId')
            ->with($customer)
            ->willReturn($storeId);
        $history->expects($this->once())->method('getType')->willReturn(1);
        $this->emailTemplateConfig->expects($this->once())
            ->method('getTemplateId')
            ->with(1, $storeId)
            ->willReturn($templateId);
        $this->emailTemplateConfig->expects($this->once())
            ->method('canSendNotification')
            ->with($customer)
            ->willReturn(true);
        $this->emailTemplateConfig->expects($this->once())->method('getCreditChangeCopyTo')->willReturn($copyTo);
        $this->emailTemplateConfig->expects($this->once())->method('getCreditCreateCopyMethod')->willReturn('copy');
        $customer->expects($this->once())->method('getEmail')->willReturn('company_admin@example.com');
        $this->accountPaymentDataFactory->expects($this->once())
            ->method('getAccountPaymentDataObject')
            ->with($history, $customer)
            ->willReturn($accountPaymentData);
        $this->emailTemplateConfig->expects($this->exactly(2))
            ->method('getSenderByStoreId')
            ->with($storeId)
            ->willReturn('sales');
        $this->transportBuilder->expects($this->exactly(2))
            ->method('setTemplateIdentifier')
            ->with($templateId)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->exactly(2))
            ->method('setTemplateOptions')
            ->with(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $storeId])
            ->willReturnSelf();
        $this->transportBuilder->expects($this->exactly(2))
            ->method('setTemplateVars')
            ->with(['accountPayment' => $accountPaymentData])
            ->willReturnSelf();
        $this->transportBuilder->expects($this->exactly(2))
            ->method('setFrom')
            ->with('sales')
            ->willReturnSelf();
        $this->transportBuilder->expects($this->exactly(2))
            ->method('addTo')
            ->withConsecutive(['company_admin@example.com'], ['info@example.com'])
            ->willReturnSelf();
        $this->transportBuilder->expects($this->exactly(2))
            ->method('addBcc')
            ->with([])
            ->willReturnSelf();
        $this->transportBuilder->expects($this->exactly(2))
            ->method('getTransport')
            ->willReturn($transport);
        $transport->expects($this->exactly(2))->method('sendMessage')->willReturnSelf();

        $this->model->sendAccountPaymentChangedNotificationEmail($history);
    }

    /**
     * Test sendAccountPaymentChangedNotificationEmail method throws exception.
     *
     * @return void
     */
    public function testSendAccountPaymentChangedNotificationEmailWithException()
    {
        $phrase = new \Magento\Framework\Phrase('Exception Message');
        $exception = new \Magento\Framework\Exception\LocalizedException($phrase);
        $history = $this->createMock(
            \ISN\PaymentAccount\Model\HistoryInterface::class
        );
        $this->notificationRecipient->expects($this->once())
            ->method('getByRecord')
            ->with($history)
            ->willThrowException($exception);
        $this->logger->expects($this->once())->method('critical')->with($exception)->willReturnSelf();

        $this->model->sendAccountPaymentChangedNotificationEmail($history);
    }
}
