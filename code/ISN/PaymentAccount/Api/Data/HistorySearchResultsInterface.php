<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ISN\PaymentAccount\Api\Data;

/**
 * Interface for History search results.
 *
 * @api
 * @since 100.0.0
 */
interface HistorySearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get History list.
     *
     * @return \ISN\PaymentAccount\Api\Data\HistoryDataInterface[]
     */
    public function getItems();

    /**
     * Set History list.
     *
     * @param \ISN\PaymentAccount\Api\Data\HistoryDataInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
