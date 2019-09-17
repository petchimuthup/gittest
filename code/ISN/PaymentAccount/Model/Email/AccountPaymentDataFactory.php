<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ISN\PaymentAccount\Model\Email;

/**
 * Class that creates DataObject containing company credit information to use in Sender class.
 */
class AccountPaymentDataFactory
{
    /**
     * @var \Magento\Framework\Reflection\DataObjectProcessor
     */
    private $dataProcessor;

    /**
     * @var \Magento\Company\Api\CompanyRepositoryInterface
     */
    private $companyRepository;

    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface
     */
    private $creditLimitRepository;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceFormatter;

    /**
     * @var \Magento\Customer\Api\CustomerNameGenerationInterface
     */
    private $customerViewHelper;

    /**
     * @var \ISN\PaymentAccount\Model\Sales\OrderLocator
     */
    private $orderLocator;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * @param \Magento\Framework\Reflection\DataObjectProcessor $dataProcessor
     * @param \Magento\Company\Api\CompanyRepositoryInterface $companyRepository
     * @param \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface $creditLimitRepository
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceFormatter
     * @param \Magento\Customer\Api\CustomerNameGenerationInterface $customerViewHelper
     * @param \ISN\PaymentAccount\Model\Sales\OrderLocator $orderLocator
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     */
    public function __construct(
        \Magento\Framework\Reflection\DataObjectProcessor $dataProcessor,
        \Magento\Company\Api\CompanyRepositoryInterface $companyRepository,
        \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface $creditLimitRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceFormatter,
        \Magento\Customer\Api\CustomerNameGenerationInterface $customerViewHelper,
        \ISN\PaymentAccount\Model\Sales\OrderLocator $orderLocator,
        \Magento\Framework\Serialize\SerializerInterface $serializer
    ) {
        $this->dataProcessor = $dataProcessor;
        $this->companyRepository = $companyRepository;
        $this->creditLimitRepository = $creditLimitRepository;
        $this->priceFormatter = $priceFormatter;
        $this->customerViewHelper = $customerViewHelper;
        $this->orderLocator = $orderLocator;
        $this->serializer = $serializer;
    }

    /**
     * Create an object with data merged from CreditHistory and Credit.
     *
     * @param \ISN\PaymentAccount\Model\HistoryInterface $history
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @return \Magento\Framework\DataObject|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAccountPaymentDataObject(
        \ISN\PaymentAccount\Model\HistoryInterface $history,
        \Magento\Customer\Api\Data\CustomerInterface $customer
    ) {
        $mergedAccountPaymentData = null;
        $orderId = null;
        $storeId = null;
        $creditLimit = $this->creditLimitRepository->get($history->getAccountPaymentId());
        $company = $this->companyRepository->get((int)$creditLimit->getCompanyId());
        $accountPaymentData = $this->dataProcessor
            ->buildOutputDataArray($history, \ISN\PaymentAccount\Model\HistoryInterface::class);
        $mergedAccountPaymentData = new \Magento\Framework\DataObject($accountPaymentData);
        $comment = $history->getComment() ? $this->serializer->unserialize($history->getComment()) : false;
        if (is_array($comment) && isset($comment['system']['order'])) {
            $orderId = $comment['system']['order'];
            $order = $this->orderLocator->getOrderByIncrementId($orderId);
            $storeId = $order->getStoreId();
        }
        $mergedAccountPaymentData->setData(
            'availableCredit',
            $this->priceFormatter->format(
                $history->getCreditLimit(),
                false,
                \Magento\Framework\Pricing\PriceCurrencyInterface::DEFAULT_PRECISION,
                $storeId,
                $history->getCurrencyCredit()
            )
        );
        $mergedAccountPaymentData->setData(
            'outStandingBalance',
            $this->priceFormatter->format(
                $history->getBalance(),
                false,
                \Magento\Framework\Pricing\PriceCurrencyInterface::DEFAULT_PRECISION,
                $storeId,
                $history->getCurrencyCredit()
            )
        );
        $mergedAccountPaymentData->setData(
            'exceedLimit',
            ($creditLimit->getExceedLimit()) ? 'allowed' : 'not allowed'
        );
        $operationAmount = $history->getAmount() * $this->getOperationAmountConversionRate($history);
        $mergedAccountPaymentData->setData(
            'operationAmount',
            $this->priceFormatter->format(
                $operationAmount,
                false,
                \Magento\Framework\Pricing\PriceCurrencyInterface::DEFAULT_PRECISION,
                $storeId,
                $history->getCurrencyCredit()
            )
        );
        $mergedAccountPaymentData->setData('orderId', $orderId);
        $mergedAccountPaymentData->setData('companyName', $company->getCompanyName());
        $mergedAccountPaymentData->setData('customerName', $this->customerViewHelper->getCustomerName($customer));

        return $mergedAccountPaymentData;
    }

    /**
     * Get rate for conversion operation amount to credit currency.
     *
     * If history item does not contain currency rate,
     * return rate between base currency and operation currency.
     * Otherwise return 1.
     *
     * @param \ISN\PaymentAccount\Model\HistoryInterface $history
     * @return float
     */
    private function getOperationAmountConversionRate(\ISN\PaymentAccount\Model\HistoryInterface $history)
    {
        $conversionRate = 1;
        $rate = (float)$history->getRate() ?: 1;
        $rateCredit = (float)$history->getRateCredit();

        if ($rateCredit) {
            $conversionRate = $rateCredit;
        } elseif ($history->getCurrencyOperation() != $history->getCurrencyCredit()) {
            $conversionRate = 1 / $rate;
        }

        return (float)$conversionRate;
    }
}
