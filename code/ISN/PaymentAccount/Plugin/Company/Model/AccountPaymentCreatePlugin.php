<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Plugin\Company\Model;

/**
 * Create company credit for company if the company does not have company credit.
 */
class AccountPaymentCreatePlugin
{
    /**
     * @var \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory
     */
    private $creditLimitFactory;

    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface
     */
    private $creditLimitRepository;

    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitManagementInterface
     */
    private $creditLimitManagement;

    /**
     * @var \Magento\Store\Api\WebsiteRepositoryInterface
     */
    private $websiteRepository;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @param \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory $creditLimitFactory
     * @param \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface $creditLimitRepository
     * @param \ISN\PaymentAccount\Api\CreditLimitManagementInterface $creditLimitManagement
     * @param \Magento\Store\Api\WebsiteRepositoryInterface $websiteRepository
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory $creditLimitFactory,
        \ISN\PaymentAccount\Api\CreditLimitRepositoryInterface $creditLimitRepository,
        \ISN\PaymentAccount\Api\CreditLimitManagementInterface $creditLimitManagement,
        \Magento\Store\Api\WebsiteRepositoryInterface $websiteRepository,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->creditLimitFactory = $creditLimitFactory;
        $this->creditLimitRepository = $creditLimitRepository;
        $this->creditLimitManagement = $creditLimitManagement;
        $this->websiteRepository = $websiteRepository;
        $this->request = $request;
    }

    /**
     * Create company credit for company if the company does not have company credit.
     *
     * @param \Magento\Company\Model\Company\Save $subject
     * @param \Magento\Company\Api\Data\CompanyInterface $company
     * @return \Magento\Company\Api\Data\CompanyInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(
        \Magento\Company\Model\Company\Save $subject,
        \Magento\Company\Api\Data\CompanyInterface $company
    ) {
        /** @var \ISN\PaymentAccount\Api\Data\CreditLimitInterface $creditLimit */
        try {
            $creditLimit = $this->creditLimitManagement->getCreditByCompanyId($company->getId());
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $creditLimit = $this->creditLimitFactory->create();
            $creditLimit->setCompanyId($company->getId());
        }
        if (!$creditLimit->getId() && !$this->request->getParam('company_credit')) {
            $creditLimit->setCompanyId($company->getId());
            $creditLimit->setCurrencyCode($this->websiteRepository->getDefault()->getBaseCurrencyCode());
            $this->creditLimitRepository->save($creditLimit);
        }
        return $company;
    }
}
