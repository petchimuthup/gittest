<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Observer;

/**
 * Perform refund when the order is canceled.
 */
class SalesOrderPaymentCancel implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @inheritdoc
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $method = $observer->getPayment()->getMethodInstance();
        if ($method->getCode() == \ISN\PaymentAccount\Model\AccountPaymentPaymentConfigProvider::METHOD_NAME) {
            $method->cancel($observer->getPayment());
        }
    }
}
