<?php


namespace Tychons\StoreManager\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface CompanyRepositoryInterface
{

    /**
     * Save Company
     * @param \Tychons\StoreManager\Api\Data\CompanyInterface $company
     * @return \Tychons\StoreManager\Api\Data\CompanyInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Tychons\StoreManager\Api\Data\CompanyInterface $company
    );

    /**
     * Retrieve Company
     * @param string $companyId
     * @return \Tychons\StoreManager\Api\Data\CompanyInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($companyId);

    /**
     * Retrieve Company matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Tychons\StoreManager\Api\Data\CompanySearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Company
     * @param \Tychons\StoreManager\Api\Data\CompanyInterface $company
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Tychons\StoreManager\Api\Data\CompanyInterface $company
    );

    /**
     * Delete Company by ID
     * @param string $companyId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($companyId);
}
