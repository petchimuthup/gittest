<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Block\Info;

/**
 * Class AccountPayment.
 */
class AccountPayment extends \Magento\Payment\Block\Info
{
    /**
     * @var string
     */
    protected $_template = 'ISN_PaymentAccount::info/accountpayment.phtml';

    /**
     * @var \ISN\PaymentAccount\Api\CreditDataProviderInterface
     */
    private $creditDataProvider;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \ISN\PaymentAccount\Api\Data\CreditDataInterface
     */
    private $creditData;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \ISN\PaymentAccount\Api\CreditDataProviderInterface $creditDataProvider
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \ISN\PaymentAccount\Api\CreditDataProviderInterface $creditDataProvider,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->creditDataProvider = $creditDataProvider;
        $this->priceCurrency = $priceCurrency;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @return string
     */
    public function toPdf()
    {
        $this->setTemplate('ISN_PaymentAccount::info/pdf/accountpayment.phtml');
        return $this->toHtml();
    }

    /**
     * Get order grand total in credit limit currency.
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getChargedAmount()
    {
        $order = $this->getInfo()->getOrder();
        $value = $order->getGrandTotal();
        if ($this->isCurrencyDifferent()) {
            $operationCurrency = $this->priceCurrency->getCurrency(true, $order->getBaseCurrencyCode());
            if ($operationCurrency->getRate($this->getCreditData()->getCurrencyCode())) {
                $value = $operationCurrency
                    ->convert($order->getBaseGrandTotal(), $this->getCreditData()->getCurrencyCode());
            }
        }

        return $this->priceCurrency->format(
            $value,
            false,
            \Magento\Framework\Pricing\PriceCurrencyInterface::DEFAULT_PRECISION,
            $order->getStoreId(),
            $this->getCreditData() ? $this->getCreditData()->getCurrencyCode() : null
        );
    }

    /**
     * Check if credit currency is different from order currency.
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function isCurrencyDifferent()
    {
        return $this->getCreditData()
            && $this->getCreditData()->getCurrencyCode() !== null
            && $this->getCreditData()->getCurrencyCode() != $this->getInfo()->getOrder()->getOrderCurrencyCode();
    }

    /**
     * Get credit data.
     *
     * @return \ISN\PaymentAccount\Api\Data\CreditDataInterface
     */
    private function getCreditData()
    {
        if ($this->creditData === null) {
            $this->creditData = $this->creditDataProvider->get($this->getCompanyId());
        }
        return $this->creditData;
    }

    /**
     * Get current company id.
     *
     * @return int|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getCompanyId()
    {
        $customerId = $this->getInfo()->getOrder()->getCustomerId();
        if ($customerId) {
            try {
                $customer = $this->customerRepository->getById($customerId);
                if ($customer->getExtensionAttributes()
                    && $customer->getExtensionAttributes()->getCompanyAttributes()
                    && $customer->getExtensionAttributes()->getCompanyAttributes()->getCompanyId()
                ) {
                    return $customer->getExtensionAttributes()->getCompanyAttributes()->getCompanyId();
                }
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                return null;
            }
        }

        return null;
    }
}
