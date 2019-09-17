<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ISN\PaymentAccount\Plugin\Customer\Api;

use \Magento\Customer\Api\CustomerRepositoryInterface;

/**
 * Class CustomerRepositoryInterfacePlugin.
 */
class CustomerRepositoryInterfacePlugin
{
    /**
     * @var \ISN\PaymentAccount\Model\HistoryRepositoryInterface
     */
    private $historyRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * GroupRepositoryInterfacePlugin constructor.
     *
     * @param \ISN\PaymentAccount\Model\HistoryRepositoryInterface $historyRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \ISN\PaymentAccount\Model\HistoryRepositoryInterface $historyRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->historyRepository = $historyRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Set user id to NULL in credit history log.
     *
     * @param CustomerRepositoryInterface $subject
     * @param \Closure $method
     * @param int $customerId
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundDeleteById(
        CustomerRepositoryInterface $subject,
        \Closure $method,
        $customerId
    ) {
        $result = $method($customerId);
        if ($result) {
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter(\ISN\PaymentAccount\Model\HistoryInterface::USER_ID, $customerId)
                ->create();
            $historyItems = $this->historyRepository->getList($searchCriteria)->getItems();
            foreach ($historyItems as $history) {
                $history->setUserId(null);
                $this->historyRepository->save($history);
            }
        }
        return $result;
    }
}
