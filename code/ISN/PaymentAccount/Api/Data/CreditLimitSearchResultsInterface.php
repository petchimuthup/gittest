<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ISN\PaymentAccount\Api\Data;

/**
 * Interface for Credit Limit search results.
 *
 * @api
 * @since 100.0.0
 */
interface CreditLimitSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get Credit Limit list.
     *
     * @return \ISN\PaymentAccount\Api\Data\CreditDataInterface[]
     */
    public function getItems();

    /**
     * Set Credit Limit list.
     *
     * @param \ISN\PaymentAccount\Api\Data\CreditDataInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
