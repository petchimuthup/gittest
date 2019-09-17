<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Observer;

use Magento\Company\Api\Data\CompanyInterface;
use ISN\PaymentAccount\Api\Data\CreditLimitInterface;
use ISN\PaymentAccount\Api\CreditLimitRepositoryInterface;
use Magento\Framework\Event\ObserverInterface;
use ISN\PaymentAccount\Model\CreditLimitHistory;
use Magento\Framework\Exception\LocalizedException;
use ISN\PaymentAccount\Api\CreditLimitManagementInterface;

/**
 * Observer for adminhtml_company_save_after event. Create or update credit limit.
 */
class AfterCompanySaveObserver implements ObserverInterface
{
    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface
     */
    private $creditLimitRepository;

    /**
     * @var \ISN\PaymentAccount\Model\CreditLimitHistory
     */
    private $creditLimitHistory;

    /**
     * @var \ISN\PaymentAccount\Model\CreditBalance
     */
    private $creditBalance;

    /**
     * @var \ISN\PaymentAccount\Model\CreditCurrency
     */
    private $creditCurrency;

    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    private $localeResolver;

    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitManagementInterface
     */
    private $creditLimitManagement;

    /**
     * @var \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory
     */
    private $creditLimitFactory;

    /**
     * AfterCompanySaveObserver constructor.
     *
     * @param CreditLimitRepositoryInterface $creditLimitRepository
     * @param CreditLimitManagementInterface $creditLimitManagement
     * @param CreditLimitHistory $creditLimitHistory
     * @param \ISN\PaymentAccount\Model\CreditBalance $creditBalance
     * @param \ISN\PaymentAccount\Model\CreditCurrency $creditCurrency
     * @param \Magento\Framework\Locale\ResolverInterface $localeResolver
     * @param \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory $creditLimitFactory
     */
    public function __construct(
        CreditLimitRepositoryInterface $creditLimitRepository,
        CreditLimitManagementInterface $creditLimitManagement,
        CreditLimitHistory $creditLimitHistory,
        \ISN\PaymentAccount\Model\CreditBalance $creditBalance,
        \ISN\PaymentAccount\Model\CreditCurrency $creditCurrency,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory $creditLimitFactory
    ) {
        $this->creditLimitRepository = $creditLimitRepository;
        $this->creditLimitManagement = $creditLimitManagement;
        $this->creditLimitHistory = $creditLimitHistory;
        $this->creditBalance = $creditBalance;
        $this->creditCurrency = $creditCurrency;
        $this->localeResolver = $localeResolver;
        $this->creditLimitFactory = $creditLimitFactory;
    }

    /**
     * After save company.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @throws LocalizedException
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $params = $observer->getRequest()->getParams();
        if (!empty($params['company_credit'])) {
            $credit = $params['company_credit'];
            /** @var CompanyInterface $company */
            $company = $observer->getCompany();
            /** @var \ISN\PaymentAccount\Api\Data\CreditLimitInterface $creditLimit */
            try {
                $creditLimit = $this->creditLimitManagement->getCreditByCompanyId($company->getId());
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                $creditLimit = $this->creditLimitFactory->create();
                $creditLimit->setCompanyId($company->getId());
            }
            $initialCurrencyCode = $creditLimit->getCurrencyCode();
            $currencyRate = $this->prepareCurrencyRate($credit);
            $creditCurrencyChanged = $this->isCreditCurrencyChanged(
                $initialCurrencyCode,
                $credit[CreditLimitInterface::CURRENCY_CODE]
            );

            if ($creditCurrencyChanged && !$currencyRate) {
                throw new LocalizedException(
                    __(
                        'Please enter a valid %1/%2 currency rate.',
                        $initialCurrencyCode,
                        $credit[CreditLimitInterface::CURRENCY_CODE]
                    )
                );
            }
            if ($creditLimit->getId()) {
                $credit[CreditLimitInterface::CREDIT_ID] = $creditLimit->getId();
            }
            $credit[CreditLimitInterface::COMPANY_ID] = $company->getId();
            $creditLimit->setData($credit);

            $creditLimit->setExceedLimit(filter_var(
                $credit[CreditLimitInterface::EXCEED_LIMIT],
                FILTER_VALIDATE_BOOLEAN
            ));
            $creditLimit->setCreditLimit($this->prepareCreditLimitValue($credit));
            $this->creditLimitRepository->save($creditLimit);
        }
    }

    /**
     * Correct locale whitespaces.
     *
     * @param string $stringToConvert
     * @return string
     */
    private function correctLocaleWhitespaces($stringToConvert)
    {
        return preg_replace('/[\pZ\pC]/u', '', $stringToConvert);
    }

    /**
     * Prepare credit limit value.
     *
     * @param array $creditData
     * @return float|null
     */
    private function prepareCreditLimitValue(array $creditData)
    {
        $creditLimitValue = null;
        if (isset($creditData[CreditLimitInterface::CREDIT_LIMIT])) {
            $numberFormatter = new \NumberFormatter($this->localeResolver->getLocale(), \NumberFormatter::DECIMAL);
            $creditLimitValue = $numberFormatter->parse(
                $this->correctLocaleWhitespaces($creditData[CreditLimitInterface::CREDIT_LIMIT])
            );
        }

        return is_float($creditLimitValue) ? $creditLimitValue : null;
    }

    /**
     * Is credit currency changed.
     *
     * @param string $initialCurrencyCode
     * @param string $currencyCode
     * @return bool
     */
    private function isCreditCurrencyChanged($initialCurrencyCode, $currencyCode)
    {
        return $initialCurrencyCode && $currencyCode && $initialCurrencyCode != $currencyCode;
    }

    /**
     * Prepare currency rate.
     *
     * @param array $credit
     * @return float|null
     */
    private function prepareCurrencyRate(array $credit)
    {
        return (isset($credit['currency_rate']) && (float)$credit['currency_rate'] > 0) ?
            (float)$credit['currency_rate'] : null;
    }
}
