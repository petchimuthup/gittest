<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Model;

use ISN\PaymentAccount\Api\Data\CreditDataInterface;

/**
 * Class CreditData.
 */
class CreditData implements CreditDataInterface
{
    /**
     * @var \ISN\PaymentAccount\Api\Data\CreditDataInterface
     */
    private $credit;

    /**
     * CreditData constructor.
     *
     * @param CreditDataInterface $credit
     */
    public function __construct(CreditDataInterface $credit)
    {
        $this->credit = $credit;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->credit->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getCompanyId()
    {
        return $this->credit->getCompanyId();
    }

    /**
     * {@inheritdoc}
     */
    public function getCreditLimit()
    {
        return $this->credit->getCreditLimit();
    }

    /**
     * {@inheritdoc}
     */
    public function getBalance()
    {
        return $this->credit->getBalance();
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrencyCode()
    {
        return $this->credit->getCurrencyCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getExceedLimit()
    {
        return $this->credit->getExceedLimit();
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableLimit()
    {
        return $this->credit->getAvailableLimit();
    }
}
