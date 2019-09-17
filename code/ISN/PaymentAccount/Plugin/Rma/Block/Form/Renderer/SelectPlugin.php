<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Plugin\Rma\Block\Form\Renderer;

use ISN\PaymentAccount\Model\AccountPaymentPaymentConfigProvider;

/**
 * Removes Store Credit option from Return form on the storefront.
 */
class SelectPlugin
{
    /**
     * Core registry.
     *
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * Remove Store Credit option from Resolution field if order placed with Payment on Account method.
     *
     * @param \Magento\Rma\Block\Form\Renderer\Select $subject
     * @param array $result
     * @return array
     */
    public function afterGetOptions(
        \Magento\Rma\Block\Form\Renderer\Select $subject,
        array $result
    ) {
        if ($subject->getAttributeObject()->getAttributeCode() == 'resolution') {
            $order = $this->coreRegistry->registry('current_order');
            if ($order
                && $order->getPayment()->getMethod() == AccountPaymentPaymentConfigProvider::METHOD_NAME
            ) {
                $result = array_filter(
                    $result,
                    function ($option) {
                        return !in_array($option['label'], ['Store Credit', __('Store Credit')]);
                    }
                );
            }
        }
        return $result;
    }
}
