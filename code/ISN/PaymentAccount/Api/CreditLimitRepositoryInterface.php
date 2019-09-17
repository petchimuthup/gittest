<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Api;

/**
 * Interface for credit limit repository for CRUD operations.
 *
 * @api
 * @since 100.0.0
 */
interface CreditLimitRepositoryInterface
{
    /**
     * Update the following company credit attributes: credit currency, credit limit and
     * setting to exceed credit.
     *
     * @param \ISN\PaymentAccount\Api\Data\CreditLimitInterface $creditLimit
     * @return \ISN\PaymentAccount\Api\Data\CreditLimitInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function save(\ISN\PaymentAccount\Api\Data\CreditLimitInterface $creditLimit);

    /**
     * Returns data on the credit limit for a specified credit limit ID.
     *
     * @param int $creditId
     * @param bool $reload [optional]
     * @return \ISN\PaymentAccount\Api\Data\CreditLimitInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($creditId, $reload = false);

    /**
     * Delete credit limit.
     *
     * @param \ISN\PaymentAccount\Api\Data\CreditLimitInterface $creditLimit
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\ISN\PaymentAccount\Api\Data\CreditLimitInterface $creditLimit);

    /**
     * Returns the list of credits for specified companies.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \ISN\PaymentAccount\Api\Data\CreditLimitSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \LogicException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
