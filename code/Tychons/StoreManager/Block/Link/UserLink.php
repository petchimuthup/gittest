<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tychons\StoreManager\Block\Link;

/**
 * Class CompanyLink.
 *
 * @api
 */
class UserLink implements \Magento\Customer\Block\Account\SortLinkInterface
{
    /**
     * @var \Magento\Company\Model\CompanyContext
     */
    private $companyContext;

    /**
     * @var \Magento\Company\Api\CompanyManagementInterface
     */
    private $companyManagement;

    /**
     * CompanyLink constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\DefaultPathInterface $defaultPath
     * @param \Magento\Company\Model\CompanyContext $companyContext
     * @param \Magento\Company\Api\CompanyManagementInterface $companyManagement
     * @param array $data [optional]
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\DefaultPathInterface $defaultPath,
        \Magento\Company\Model\CompanyContext $companyContext,
        \Magento\Company\Api\CompanyManagementInterface $companyManagement,
        array $data = []
    ) {
        parent::__construct($context, $defaultPath, $companyContext, $data);
        $this->companyContext = $companyContext;
        $this->companyManagement = $companyManagement;
    }

    /**
     * @return bool
     */
    protected function isVisible()
    {

        $role = $this->userRole();

        if($role == 3 || empty($role)){

            return;
        }
        
        $company = null;
        $isRegistrationAllowed = $this->companyContext->isStorefrontRegistrationAllowed();
        if ($this->companyContext->getCustomerId()) {
            $company = $this->companyManagement->getByCustomerId($this->companyContext->getCustomerId());
        }
        return !$company && $isRegistrationAllowed || parent::isVisible();
    }

    /**
     * {@inheritdoc}
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

        public function getCustomerId()
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customerSession = $objectManager->create('Magento\Customer\Model\Session');

        $customerId = $customerSession->getCustomer()->getId();

        if (!empty($customerId)) {
           
           return $customerId;

        }else{

            return;
        }
    }

    public function userRole()
    {

        $customerId = $this->getCustomerId();

        if (empty($customerId)) {
           
           return;

        }

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customer = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);

        return $customer->getRoleId();

    }
}
