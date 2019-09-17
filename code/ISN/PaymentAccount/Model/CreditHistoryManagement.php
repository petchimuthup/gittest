<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Model;

/**
 * Update credit history log and retrieve history which match a specified criteria.
 */
class CreditHistoryManagement implements \ISN\PaymentAccount\Api\CreditHistoryManagementInterface
{
    /**
     * @var \ISN\PaymentAccount\Model\ResourceModel\History
     */
    private $historyResource;

    /**
     * @var \ISN\PaymentAccount\Model\ResourceModel\History\CollectionFactory
     */
    private $historyCollectionFactory;

    /**
     * @var \Magento\Framework\Api\SearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var \ISN\PaymentAccount\Model\HistoryRepositoryInterface
     */
    private $historyRepository;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * @param \ISN\PaymentAccount\Model\ResourceModel\History $historyResource
     * @param \ISN\PaymentAccount\Model\ResourceModel\History\CollectionFactory $historyCollectionFactory
     * @param \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory
     * @param \ISN\PaymentAccount\Model\HistoryRepositoryInterface $historyRepository
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     */
    public function __construct(
        \ISN\PaymentAccount\Model\ResourceModel\History $historyResource,
        \ISN\PaymentAccount\Model\ResourceModel\History\CollectionFactory $historyCollectionFactory,
        \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory,
        \ISN\PaymentAccount\Model\HistoryRepositoryInterface $historyRepository,
        \Magento\Framework\Serialize\SerializerInterface $serializer
    ) {
        $this->historyResource = $historyResource;
        $this->historyCollectionFactory = $historyCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->historyRepository = $historyRepository;
        $this->serializer = $serializer;
    }

    /**
     * @inheritdoc
     */
    public function update($historyId, $purchaseOrder = null, $comment = null)
    {
        $history = $this->historyRepository->get($historyId);
        if ($history->getType() != HistoryInterface::TYPE_REIMBURSED) {
            throw new \Magento\Framework\Exception\InputException(
                __('Cannot process the request. Please check the operation type and try again.')
            );
        }
        if ($purchaseOrder !== null) {
            $history->setPurchaseOrder($purchaseOrder);
        }
        if ($comment !== null) {
            $history->setPurchaseOrder($purchaseOrder);
            $commentArray = $history->getComment() ? $this->serializer->unserialize($history->getComment()) : [];
            $commentArray['custom'] = $comment;
            $history->setComment($this->serializer->serialize($commentArray));
        }
        try {
            $this->historyResource->save($history);
        } catch (\Magento\Framework\Exception\CouldNotSaveException $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('Could not update history'),
                $e
            );
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        /** @var \ISN\PaymentAccount\Model\ResourceModel\History\Collection $collection */
        $collection = $this->historyCollectionFactory->create();

        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? : 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    $sortOrder->getDirection()
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());

        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }
}
