<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Model;

use ISN\PaymentAccount\Api\CreditDataProviderInterface;
use ISN\PaymentAccount\Api\CreditLimitManagementInterface;
use ISN\PaymentAccount\Model\CreditDataFactory;

/**
 * Class CreditDataProvider.
 */
class CreditDataProvider implements CreditDataProviderInterface
{
    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitManagementInterface
     */
    private $creditLimitManagement;

    /**
     * @var \ISN\PaymentAccount\Model\CreditDataFactory
     */
    private $creditDataFactory;

    /**
     * Constructor.
     *
     * @param CreditLimitManagementInterface $creditLimitManagement
     * @param \ISN\PaymentAccount\Model\CreditDataFactory $creditDataFactory
     */
    public function __construct(
        CreditLimitManagementInterface $creditLimitManagement,
        CreditDataFactory $creditDataFactory
    ) {
        $this->creditLimitManagement = $creditLimitManagement;
        $this->creditDataFactory = $creditDataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function get($companyId)
    {
        $creditObject = $this->creditLimitManagement->getCreditByCompanyId($companyId);
        return $this->creditDataFactory->create(['credit' => $creditObject]);
    }
}
