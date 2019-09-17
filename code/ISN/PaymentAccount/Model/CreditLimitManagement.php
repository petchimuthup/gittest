<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Model;

use ISN\PaymentAccount\Api\CreditLimitManagementInterface;
use ISN\PaymentAccount\Model\ResourceModel\CreditLimit as CreditLimitResource;
use ISN\PaymentAccount\Api\Data\CreditLimitInterface;
use ISN\PaymentAccount\Model\ResourceModel\CreditLimit\CollectionFactory as CreditLimitCollectionFactory;

/**
 * Management the credit limit for a specified company.
 */
class CreditLimitManagement implements CreditLimitManagementInterface
{
    /**
     * @var \ISN\PaymentAccount\Model\CreditLimitFactory
     */
    private $creditLimitFactory;

    /**
     * @var \ISN\PaymentAccount\Model\ResourceModel\CreditLimit
     */
    private $creditLimitResource;

    /**
     * CreditLimitRepository constructor.
     *
     * @param \ISN\PaymentAccount\Model\CreditLimitFactory $creditLimitFactory
     * @param CreditLimitResource $creditLimitResource
     */
    public function __construct(
        \ISN\PaymentAccount\Model\CreditLimitFactory $creditLimitFactory,
        CreditLimitResource $creditLimitResource
    ) {
        $this->creditLimitFactory = $creditLimitFactory;
        $this->creditLimitResource = $creditLimitResource;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreditByCompanyId($companyId)
    {
        /** @var \ISN\PaymentAccount\Api\Data\CreditLimitInterface $creditLimit */
        $creditLimit = $this->creditLimitFactory->create();
        $this->creditLimitResource->load($creditLimit, $companyId, CreditLimitInterface::COMPANY_ID);
        if (!$creditLimit->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __(
                    'Requested company is not found. Row ID: %fieldName = %fieldValue.',
                    ['fieldName' => 'CompanyID', 'fieldValue' => $companyId]
                )
            );
        }
        return $creditLimit;
    }
}
