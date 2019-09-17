<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ISN\PaymentAccount\Action;

use Magento\Company\Api\CompanyRepositoryInterface;
use ISN\PaymentAccount\Api\CreditBalanceManagementInterface;
use ISN\PaymentAccount\Api\CreditLimitManagementInterface;
use ISN\PaymentAccount\Api\Data\CreditBalanceOptionsInterface;
use ISN\PaymentAccount\Api\Data\CreditBalanceOptionsInterfaceFactory;
use ISN\PaymentAccount\Api\Data\CreditLimitInterface;
use ISN\PaymentAccount\Model\HistoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Facade for reimburse action.
 */
class ReimburseFacade
{
    /**
     * @var CreditBalanceManagementInterface
     */
    private $creditBalanceManagement;

    /**
     * @var CompanyRepositoryInterface
     */
    private $companyRepository;

    /**
     * @var CreditLimitManagementInterface
     */
    private $creditLimitManagement;

    /**
     * @var CreditBalanceOptionsInterfaceFactory
     */
    private $creditBalanceOptionsFactory;

    /**
     * @param CreditLimitManagementInterface $creditLimitManagement
     * @param CompanyRepositoryInterface $companyRepository
     * @param CreditBalanceManagementInterface $creditBalanceManagement
     * @param CreditBalanceOptionsInterfaceFactory $creditBalanceOptionsFactory
     */
    public function __construct(
        CreditLimitManagementInterface $creditLimitManagement,
        CompanyRepositoryInterface $companyRepository,
        CreditBalanceManagementInterface $creditBalanceManagement,
        CreditBalanceOptionsInterfaceFactory $creditBalanceOptionsFactory
    ) {
        $this->creditLimitManagement = $creditLimitManagement;
        $this->companyRepository = $companyRepository;
        $this->creditBalanceManagement = $creditBalanceManagement;
        $this->creditBalanceOptionsFactory = $creditBalanceOptionsFactory;
    }

    /**
     * Execute company credit reimburse.
     *
     * @param int $companyId
     * @param float $amount
     * @param string $comment
     * @param string $purchaseOrder
     * @return CreditLimitInterface
     * @throws NoSuchEntityException
     */
    public function execute($companyId, $amount, $comment, $purchaseOrder)
    {
        $company = $this->companyRepository->get($companyId);
        $credit = $this->creditLimitManagement->getCreditByCompanyId($company->getId());

        /** @var CreditBalanceOptionsInterface $options */
        $options = $this->creditBalanceOptionsFactory->create();
        $options->setPurchaseOrder($purchaseOrder);

        if ($amount > 0) {
            $this->creditBalanceManagement->increase(
                $credit->getId(),
                $amount,
                $credit->getCurrencyCode(),
                HistoryInterface::TYPE_REIMBURSED,
                $comment,
                $options
            );
        } else {
            $this->creditBalanceManagement->decrease(
                $credit->getId(),
                -$amount,
                $credit->getCurrencyCode(),
                HistoryInterface::TYPE_REIMBURSED,
                $comment,
                $options
            );
        }

        return $this->creditLimitManagement->getCreditByCompanyId($company->getId());
    }
}
