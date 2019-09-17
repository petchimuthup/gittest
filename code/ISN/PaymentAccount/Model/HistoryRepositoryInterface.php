<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Model;

/**
 * History repository interface.
 */
interface HistoryRepositoryInterface
{
    /**
     * Create credit limit.
     *
     * @param \ISN\PaymentAccount\Model\HistoryInterface $history
     * @return \ISN\PaymentAccount\Model\HistoryInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\ISN\PaymentAccount\Model\HistoryInterface $history);

    /**
     * Get credit limit.
     *
     * @param int $historyId
     * @return \ISN\PaymentAccount\Model\HistoryInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($historyId);

    /**
     * Delete credit limit.
     *
     * @param \ISN\PaymentAccount\Model\HistoryInterface $history
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\ISN\PaymentAccount\Model\HistoryInterface $history);

    /**
     * Retrieve credit limits which match a specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
