<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Plugin\Company\Controller\Adminhtml\Index;

/**
 * Class adds notice message if company credit currency is not among websites' base currencies.
 */
class EditPlugin
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var \ISN\PaymentAccount\Api\CreditLimitManagementInterface
     */
    private $creditLimitManagement;

    /**
     * @var \ISN\PaymentAccount\Model\WebsiteCurrency
     */
    private $websiteCurrency;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;

    /**
     * @var \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory
     */
    private $creditLimitFactory;

    /**
     * EditPlugin constructor.
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \ISN\PaymentAccount\Api\CreditLimitManagementInterface $creditLimitManagement
     * @param \ISN\PaymentAccount\Model\WebsiteCurrency $websiteCurrency
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory $creditLimitFactory
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \ISN\PaymentAccount\Api\CreditLimitManagementInterface $creditLimitManagement,
        \ISN\PaymentAccount\Model\WebsiteCurrency $websiteCurrency,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \ISN\PaymentAccount\Api\Data\CreditLimitInterfaceFactory $creditLimitFactory
    ) {
        $this->request = $request;
        $this->creditLimitManagement = $creditLimitManagement;
        $this->websiteCurrency = $websiteCurrency;
        $this->messageManager = $messageManager;
        $this->creditLimitFactory = $creditLimitFactory;
    }

    /**
     * Before execute.
     *
     * @param \Magento\Company\Controller\Adminhtml\Index\Edit $subject
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeExecute(\Magento\Company\Controller\Adminhtml\Index\Edit $subject)
    {
        $companyId = $this->request->getParam('id');

        if ($companyId) {
            /** @var \ISN\PaymentAccount\Api\Data\CreditLimitInterface $creditLimit */
            try {
                $creditLimit = $this->creditLimitManagement->getCreditByCompanyId($companyId);
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                $creditLimit = $this->creditLimitFactory->create();
                $creditLimit->setCompanyId($companyId);
            }
            $creditCurrencyCode = $creditLimit->getCurrencyCode();

            if ($creditCurrencyCode && !$this->websiteCurrency->isCreditCurrencyEnabled($creditCurrencyCode)) {
                $this->messageManager->addNoticeMessage(
                    __(
                        'The selected credit currency is not valid. 
                        Customers will not be able to place orders until you update the credit currency.'
                    )
                );
            }
        }

        return [];
    }
}
