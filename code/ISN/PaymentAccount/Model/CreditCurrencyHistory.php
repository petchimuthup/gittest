<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Model;

/**
 * Class performs history log updates during credit currency changes.
 */
class CreditCurrencyHistory
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
     * @var \Magento\Framework\DB\TransactionFactory
     */
    private $transactionFactory;

    /**
     * CreditCurrencyHistory constructor.
     *
     * @param \ISN\PaymentAccount\Model\HistoryRepositoryInterface $historyRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     */
    public function __construct(
        \ISN\PaymentAccount\Model\HistoryRepositoryInterface $historyRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\DB\TransactionFactory $transactionFactory
    ) {
        $this->historyRepository = $historyRepository;
        $this->transactionFactory = $transactionFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Update company credit ids in company credit history.
     *
     * @param int $currentAccountPaymentId
     * @param int $accountPaymentId
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    public function update($currentAccountPaymentId, $accountPaymentId)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(\ISN\PaymentAccount\Model\HistoryInterface::COMPANY_CREDIT_ID, $currentAccountPaymentId)
            ->create();
        $historyItems = $this->historyRepository->getList($searchCriteria)->getItems();

        if ($historyItems) {
            $transaction = $this->transactionFactory->create();

            /**
             * @var \ISN\PaymentAccount\Model\HistoryInterface $historyItem
             */
            foreach ($historyItems as $historyItem) {
                $historyItem->setAccountPaymentId($accountPaymentId);
                $transaction->addObject($historyItem);
            }

            $transaction->save();
        }
    }
}
