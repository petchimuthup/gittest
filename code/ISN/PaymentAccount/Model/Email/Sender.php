<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Model\Email;

/**
 * Class Sender.
 */
class Sender
{
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \ISN\PaymentAccount\Model\Email\AccountPaymentDataFactory
     */
    private $accountPaymentDataFactory;

    /**
     * @var \ISN\PaymentAccount\Model\Config\EmailTemplate
     */
    private $emailTemplateConfig;

    /**
     * @var NotificationRecipientLocator
     */
    private $notificationRecipient;

    /**
     * Email sender constructor.
     *
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Psr\Log\LoggerInterface $logger
     * @param AccountPaymentDataFactory $accountPaymentDataFactory
     * @param \ISN\PaymentAccount\Model\Config\EmailTemplate $emailTemplateConfig
     * @param NotificationRecipientLocator $notificationRecipient
     */
    public function __construct(
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Psr\Log\LoggerInterface $logger,
        AccountPaymentDataFactory $accountPaymentDataFactory,
        \ISN\PaymentAccount\Model\Config\EmailTemplate $emailTemplateConfig,
        NotificationRecipientLocator $notificationRecipient
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->logger = $logger;
        $this->accountPaymentDataFactory = $accountPaymentDataFactory;
        $this->emailTemplateConfig = $emailTemplateConfig;
        $this->notificationRecipient = $notificationRecipient;
    }

    /**
     * Notify company admin of company credit changes.
     *
     * @param \ISN\PaymentAccount\Model\HistoryInterface $history
     * @return $this
     */
    public function sendAccountPaymentChangedNotificationEmail(\ISN\PaymentAccount\Model\HistoryInterface $history)
    {
        try {
            $companySuperUser = $this->notificationRecipient->getByRecord($history);
            $storeId = $companySuperUser->getStoreId();
            if (!$storeId) {
                $storeId = $this->emailTemplateConfig->getDefaultStoreId($companySuperUser);
            }
            $templateId = $this->emailTemplateConfig->getTemplateId($history->getType(), $storeId);
            if ($this->emailTemplateConfig->canSendNotification($companySuperUser) && $templateId) {
                $copyTo = $this->emailTemplateConfig->getCreditChangeCopyTo();
                $copyMethod = $this->emailTemplateConfig->getCreditCreateCopyMethod();
                $sendTo = $this->getSendTo($copyTo, $copyMethod, $companySuperUser);
                $accountPaymentData = $this->accountPaymentDataFactory->getAccountPaymentDataObject(
                    $history,
                    $companySuperUser
                );
                if ($accountPaymentData !== null) {
                    foreach ($sendTo as $recipient) {
                        $this->sendEmailTemplate(
                            $recipient,
                            $accountPaymentData->getData('customerName'),
                            $templateId,
                            $this->emailTemplateConfig->getSenderByStoreId($storeId),
                            [
                                'accountPayment' => $accountPaymentData
                            ],
                            $storeId,
                            ($copyTo && $copyMethod == 'bcc') ? explode(',', $copyTo) : []
                        );
                    }
                }
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->logger->critical($e);
        }

        return $this;
    }

    /**
     * Get copyTo email array.
     *
     * @param string $copyTo
     * @param string $copyMethod
     * @param \Magento\Customer\Api\Data\CustomerInterface $companySuperUser
     * @return array
     */
    private function getSendTo($copyTo, $copyMethod, $companySuperUser)
    {
        $sendTo = [];
        if ($copyTo && $copyMethod == 'copy') {
            $sendTo = explode(',', $copyTo);
        }
        array_unshift($sendTo, $companySuperUser->getEmail());

        return $sendTo;
    }

    /**
     * Send corresponding email template.
     *
     * @param string $customerEmail
     * @param string $customerName
     * @param string $templateId
     * @param string|array $sender configuration path of email identity
     * @param array $templateParams
     * @param int|null $storeId
     * @param string|array $bcc
     * @return void
     * @throws \Magento\Framework\Exception\MailException
     */
    private function sendEmailTemplate(
        $customerEmail,
        $customerName,
        $templateId,
        $sender,
        array $templateParams = [],
        $storeId = null,
        $bcc = []
    ) {
        $this->transportBuilder->setTemplateIdentifier($templateId);
        $this->transportBuilder->setTemplateOptions(
            ['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $storeId]
        );
        $this->transportBuilder->setTemplateVars($templateParams);
        $this->transportBuilder->setFrom($sender);
        $this->transportBuilder->addTo($customerEmail, $customerName);
        $this->transportBuilder->addBcc($bcc);
        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();
    }
}
