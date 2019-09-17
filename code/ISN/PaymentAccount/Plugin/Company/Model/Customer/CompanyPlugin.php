<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Plugin\Company\Model\Customer;

/**
 * Create company credit for new companies.
 */
class CompanyPlugin
{
    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface
     */
    private $creditLimitRepository;

    /**
     * @var \Magento\Store\Api\WebsiteRepositoryInterface
     */
    private $websiteRepository;

    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitManagementInterface
     */
    private $creditLimitManagement;

    /**
     * @var \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory
     */
    private $creditLimitFactory;

    /**
     * @param \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface $creditLimitRepository
     * @param \ISN\PaymentAccount\Api\CreditLimitManagementInterface $creditLimitManagement
     * @param \Magento\Store\Api\WebsiteRepositoryInterface $websiteRepository
     * @param \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory $creditLimitFactory
     */
    public function __construct(
        \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface $creditLimitRepository,
        \ISN\PaymentAccount\Api\CreditLimitManagementInterface $creditLimitManagement,
        \Magento\Store\Api\WebsiteRepositoryInterface $websiteRepository,
        \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory $creditLimitFactory
    ) {
        $this->creditLimitRepository = $creditLimitRepository;
        $this->creditLimitManagement = $creditLimitManagement;
        $this->creditLimitFactory = $creditLimitFactory;
        $this->websiteRepository = $websiteRepository;
    }

    /**
     * Save company credit after company creation.
     *
     * @param \Magento\Company\Model\Customer\Company $subject
     * @param \Magento\Company\Api\Data\CompanyInterface $company
     * @return \Magento\Company\Api\Data\CompanyInterface
     * @throws \DomainException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterCreateCompany(
        \Magento\Company\Model\Customer\Company $subject,
        \Magento\Company\Api\Data\CompanyInterface $company
    ) {
        /** @var \ISN\PaymentAccount\Api\Data\CreditLimitInterface $creditLimit */
        try {
            $creditLimit = $this->creditLimitManagement->getCreditByCompanyId($company->getId());
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $creditLimit = $this->creditLimitFactory->create();
            $creditLimit->setCompanyId($company->getId());
        }
        $creditLimit->setCurrencyCode($this->websiteRepository->getDefault()->getBaseCurrencyCode());
        $this->creditLimitRepository->save($creditLimit);
        return $company;
    }
}
