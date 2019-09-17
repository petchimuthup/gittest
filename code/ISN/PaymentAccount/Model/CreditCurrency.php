<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Model;

use ISN\PaymentAccount\Api\Data\CreditLimitInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\InputException;

/**
 * Class creates new accountPayment object, updates history log, removes old accountPayment object.
 */
class CreditCurrency
{
    /**
     * @var \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory
     */
    private $creditLimitFactory;

    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface
     */
    private $creditLimitRepository;

    /**
     * @var \ISN\PaymentAccount\Model\WebsiteCurrency
     */
    private $websiteCurrency;

    /**
     * @var \ISN\PaymentAccount\Model\CreditCurrencyHistory
     */
    private $creditCurrencyHistory;

    /**
     * @var \ISN\PaymentAccount\Model\CreditLimitHistory
     */
    private $creditLimitHistory;

    /**
     * @param \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory $creditLimitFactory
     * @param \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface $creditLimitRepository
     * @param CreditCurrencyHistory $creditCurrencyHistory
     * @param WebsiteCurrency $websiteCurrency
     * @param CreditLimitHistory $creditLimitHistory
     */
    public function __construct(
        \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory $creditLimitFactory,
        \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface $creditLimitRepository,
        CreditCurrencyHistory $creditCurrencyHistory,
        WebsiteCurrency $websiteCurrency,
        CreditLimitHistory $creditLimitHistory
    ) {
        $this->creditLimitFactory = $creditLimitFactory;
        $this->creditLimitRepository = $creditLimitRepository;
        $this->creditCurrencyHistory = $creditCurrencyHistory;
        $this->websiteCurrency = $websiteCurrency;
        $this->creditLimitHistory = $creditLimitHistory;
    }

    /**
     * Update company credit data.
     *
     * @param CreditLimitInterface $currentCreditLimit
     * @param array $accountPaymentData
     * @param float $currencyRate
     * @return CreditLimitInterface
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     * @throws InputException
     * @throws LocalizedException
     * @throws \Exception
     */
    public function change(
        CreditLimitInterface $currentCreditLimit,
        array $accountPaymentData,
        $currencyRate
    ) {
        if (!$this->websiteCurrency->isCreditCurrencyEnabled($accountPaymentData[CreditLimitInterface::CURRENCY_CODE])) {
            throw new LocalizedException(
                __('The selected currency is not available. Please select a different currency.')
            );
        }

        /**
         * @var \ISN\PaymentAccount\Api\Data\CreditLimitInterface $creditLimit
         */
        $creditLimit = $this->creditLimitFactory->create();
        $accountPaymentData[CreditLimitInterface::COMPANY_ID] = $currentCreditLimit->getCompanyId();
        $accountPaymentData[CreditLimitInterface::BALANCE] = $this->calculateBalance(
            $currentCreditLimit,
            $currencyRate
        );
        $accountPaymentData[CreditLimitInterface::CREDIT_LIMIT] =
            $accountPaymentData[CreditLimitInterface::CREDIT_LIMIT] ?: null;
        $creditLimit->setData($accountPaymentData);
        $this->creditLimitRepository->save($creditLimit);
        $this->creditCurrencyHistory->update($currentCreditLimit->getId(), $creditLimit->getId());
        $this->creditLimitRepository->delete($currentCreditLimit);
        $comment = $this->creditLimitHistory->prepareChangeCurrencyComment(
            $currentCreditLimit->getCurrencyCode(),
            $accountPaymentData[CreditLimitInterface::CURRENCY_CODE],
            $currencyRate
        );
        $this->creditLimitHistory->logUpdateCreditLimit(
            $creditLimit,
            '',
            [
                HistoryInterface::COMMENT_TYPE_UPDATE_CURRENCY => $comment
            ]
        );
        return $creditLimit;
    }

    /**
     * Calculate new credit limit based on currency rate.
     *
     * @param CreditLimitInterface $creditLimit
     * @param float $currencyRate
     * @return float
     */
    private function calculateBalance(CreditLimitInterface $creditLimit, $currencyRate)
    {
        $currentBalance = $creditLimit->getBalance();

        return $currentBalance * $currencyRate;
    }
}
