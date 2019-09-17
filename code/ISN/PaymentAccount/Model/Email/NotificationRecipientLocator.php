<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Model\Email;

/**
 * Class that retrieves email notification recipient to use in Sender class.
 */
class NotificationRecipientLocator
{
    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface
     */
    private $creditLimitRepository;

    /**
     * @var \Magento\Company\Api\CompanyManagementInterface
     */
    private $companyManagement;

    /**
     * NotificationRecipient constructor.
     *
     * @param \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface $creditLimitRepository
     * @param \Magento\Company\Api\CompanyManagementInterface $companyManagement
     */
    public function __construct(
        \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface $creditLimitRepository,
        \Magento\Company\Api\CompanyManagementInterface $companyManagement
    ) {
        $this->creditLimitRepository = $creditLimitRepository;
        $this->companyManagement = $companyManagement;
    }

    /**
     * Get company admin by credit history record.
     *
     * @param \ISN\PaymentAccount\Model\HistoryInterface $creditHistoryRecord
     * @return \Magento\Customer\Api\Data\CustomerInterface|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByRecord(\ISN\PaymentAccount\Model\HistoryInterface $creditHistoryRecord)
    {
        $creditLimit = $this->creditLimitRepository->get($creditHistoryRecord->getAccountPaymentId());
        $companySuperUser = $this->companyManagement->getAdminByCompanyId($creditLimit->getCompanyId());

        return $companySuperUser;
    }
}
