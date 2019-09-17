<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Block\Adminhtml\Order;

use ISN\PaymentAccount\Model\AccountPaymentPaymentConfigProvider;

/**
 * Changes confirmation message for Cancel button if company is not active.
 *
 * @api
 * @since 100.0.0
 */
class CancelButton extends \Magento\Sales\Block\Adminhtml\Order\View
{
    /**
     * @var \ISN\PaymentAccount\Model\CompanyStatus
     */
    private $companyStatus;

    /**
     * @var \ISN\PaymentAccount\Model\CompanyOrder
     */
    private $companyOrder;

    /**
     * @var \Magento\Company\Api\CompanyRepositoryInterface
     */
    private $companyRepository;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Sales\Model\Config $salesConfig
     * @param \Magento\Sales\Helper\Reorder $reorderHelper
     * @param \ISN\PaymentAccount\Model\CompanyStatus $companyStatus
     * @param \ISN\PaymentAccount\Model\CompanyOrder $companyOrder
     * @param \Magento\Company\Api\CompanyRepositoryInterface $companyRepository
     * @param array $data [optional]
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Model\Config $salesConfig,
        \Magento\Sales\Helper\Reorder $reorderHelper,
        \ISN\PaymentAccount\Model\CompanyStatus $companyStatus,
        \ISN\PaymentAccount\Model\CompanyOrder $companyOrder,
        \Magento\Company\Api\CompanyRepositoryInterface $companyRepository,
        array $data = []
    ) {
        $this->companyStatus = $companyStatus;
        $this->companyOrder = $companyOrder;
        $this->companyRepository = $companyRepository;
        parent::__construct($context, $registry, $salesConfig, $reorderHelper, $data);
    }

    /**
     * Replace confirmation message for Cancel button if company is not active or doesn't exist.
     *
     * @return $this
     */
    public function checkCompanyStatus()
    {
        $order = $this->getOrder();
        if ($order->getId()
            && $order->getPayment()->getMethod() == AccountPaymentPaymentConfigProvider::METHOD_NAME
        ) {
            $confirm = null;
            $companyId = $this->companyOrder->getCompanyIdByOrder($order);
            if ($companyId && !$this->companyStatus->isRevertAvailable($companyId)) {
                try {
                    $company = $this->companyRepository->get($companyId);
                    $confirm = __(
                        'Are you sure you want to cancel this order? '
                        . 'The order amount will not be reverted to %1 because the company is not active.',
                        $company->getCompanyName()
                    );
                } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                    $companyId = null;
                }
            }

            if (!$companyId) {
                $companyName = $order->getPayment()->getAdditionalInformation('company_name');
                $confirm = __(
                    'Are you sure you want to cancel this order? The order amount will not be reverted '
                    . 'to %1 because the company associated with this customer does not exist.',
                    $companyName
                );
            }

            if ($confirm) {
                $this->updateButton(
                    'order_cancel',
                    'data_attribute',
                    [
                        'mage-init' => '{"ISN_PaymentAccount/js/cancel-order-button": '
                            . '{"message": "' . $confirm . '", "url": "' . $this->getCancelUrl() . '"}}',
                    ]
                );
            }
        }
        return $this;
    }
}
