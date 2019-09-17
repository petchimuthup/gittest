<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Model;

/**
 * Credit limit repository for CRUD operations.
 */
class CreditLimitRepository implements \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface
{
    /**
     * @var \ISN\PaymentAccount\Api\Data\CreditLimitInterface[]
     */
    private $instancesById = [];

    /**
     * @var \ISN\PaymentAccount\Model\CreditLimitFactory
     */
    private $creditLimitFactory;

    /**
     * @var \ISN\PaymentAccount\Model\ResourceModel\CreditLimit
     */
    private $creditLimitResource;

    /**
     * @var \ISN\PaymentAccount\Model\SaveHandler
     */
    private $saveHandler;

    /**
     * @var \ISN\PaymentAccount\Model\Validator
     */
    private $validator;

    /**
     * @var \ISN\PaymentAccount\Model\CreditLimit\SearchProvider
     */
    private $searchProvider;

    /**
     * @param \ISN\PaymentAccount\Model\CreditLimitFactory $creditLimitFactory
     * @param \ISN\PaymentAccount\Model\ResourceModel\CreditLimit $creditLimitResource
     * @param \ISN\PaymentAccount\Model\Validator $validator
     * @param \ISN\PaymentAccount\Model\SaveHandler $saveHandler
     * @param \ISN\PaymentAccount\Model\CreditLimit\SearchProvider $accountPaymentSearchProvider
     */
    public function __construct(
        \ISN\PaymentAccount\Model\CreditLimitFactory $creditLimitFactory,
        \ISN\PaymentAccount\Model\ResourceModel\CreditLimit $creditLimitResource,
        \ISN\PaymentAccount\Model\Validator $validator,
        \ISN\PaymentAccount\Model\SaveHandler $saveHandler,
        \ISN\PaymentAccount\Model\CreditLimit\SearchProvider $accountPaymentSearchProvider
    ) {
        $this->creditLimitFactory = $creditLimitFactory;
        $this->creditLimitResource = $creditLimitResource;
        $this->validator = $validator;
        $this->saveHandler = $saveHandler;
        $this->searchProvider = $accountPaymentSearchProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function save(\ISN\PaymentAccount\Api\Data\CreditLimitInterface $creditLimit)
    {
        $prevCreditLimitData = $creditLimit->getData();
        $creditCurrencyChanged = false;
        $originalCreditLimit = null;
        if ($creditLimit->getId()) {
            $originalCreditLimit = $this->get($creditLimit->getId());
        }
        $this->validator->validateCreditData($prevCreditLimitData);
        if ($creditLimit->getId()) {
            $currencyFrom = $originalCreditLimit->getCurrencyCode();
            $currencyTo = $prevCreditLimitData[\ISN\PaymentAccount\Api\Data\CreditLimitInterface::CURRENCY_CODE];
            $creditCurrencyChanged = $currencyFrom != $currencyTo;
        }
        $this->saveHandler->execute($creditLimit);
        if ($creditCurrencyChanged) {
            $this->delete($originalCreditLimit);
        }
        return $creditLimit;
    }

    /**
     * {@inheritdoc}
     */
    public function get($creditId, $reload = false)
    {
        if (!isset($this->instancesById[$creditId]) || $reload) {
            /** @var \ISN\PaymentAccount\Api\Data\CreditLimitInterface $creditLimit */
            $creditLimit = $this->creditLimitFactory->create();
            $this->creditLimitResource->load($creditLimit, $creditId);
            $this->validator->checkAccountPaymentExist($creditLimit, $creditId);
            $this->instancesById[$creditId] = $creditLimit;
        }
        return $this->instancesById[$creditId];
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\ISN\PaymentAccount\Api\Data\CreditLimitInterface $creditLimit)
    {
        try {
            $id = $creditLimit->getId();
            $this->creditLimitResource->delete($creditLimit);
            unset($this->instancesById[$id]);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(
                __(
                    'Cannot delete credit limit with id %1',
                    $creditLimit->getId()
                ),
                $e
            );
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        return $this->searchProvider->getList($searchCriteria);
    }
}
