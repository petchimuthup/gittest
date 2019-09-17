<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Model\CreditDetails;

use Magento\Authorization\Model\UserContextInterface;

/**
 * Class CustomerProvider.
 */
class CustomerProvider
{
    /**
     * @var \ISN\PaymentAccount\Api\CreditDataProviderInterface
     */
    private $creditDataProvider;

    /**
     * @var UserContextInterface
     */
    private $userContext;

    /**
     * @var \Magento\Company\Api\CompanyManagementInterface
     */
    private $companyManagement;

    /**
     * AdminProvider constructor.
     *
     * @param \ISN\PaymentAccount\Api\CreditDataProviderInterface $creditDataProvider
     * @param \Magento\Authorization\Model\UserContextInterface $userContext
     * @param \Magento\Company\Api\CompanyManagementInterface $companyManagement
     */
    public function __construct(
        \ISN\PaymentAccount\Api\CreditDataProviderInterface $creditDataProvider,
        \Magento\Authorization\Model\UserContextInterface $userContext,
        \Magento\Company\Api\CompanyManagementInterface $companyManagement
    ) {
        $this->creditDataProvider = $creditDataProvider;
        $this->userContext = $userContext;
        $this->companyManagement = $companyManagement;
    }

    /**
     * Get current user credit.
     *
     * @return \ISN\PaymentAccount\Api\Data\CreditDataInterface|null
     */
    public function getCurrentUserCredit()
    {
        $credit = null;

        if ($this->userContext->getUserId()
            && $this->userContext->getUserType()
            === \Magento\Authorization\Model\UserContextInterface::USER_TYPE_CUSTOMER
        ) {
            $company = $this->companyManagement->getByCustomerId($this->userContext->getUserId());

            if ($company) {
                $credit = $this->creditDataProvider->get($company->getId());
            }
        }

        return $credit;
    }
}
