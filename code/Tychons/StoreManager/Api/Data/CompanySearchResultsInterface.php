<?php


namespace Tychons\StoreManager\Api\Data;

interface CompanySearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Company list.
     * @return \Tychons\StoreManager\Api\Data\CompanyInterface[]
     */
    public function getItems();

    /**
     * Set firstname list.
     * @param \Tychons\StoreManager\Api\Data\CompanyInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
