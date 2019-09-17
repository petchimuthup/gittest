<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Observer for payment_method_assign_data_accountpayment event. We add purchase order number to payment model here.
 */
class AssignPaymentMethodDataObserver implements ObserverInterface
{
    /**
     * Add purchase order number.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $poNumber = $observer->getData(\Magento\Payment\Observer\AbstractDataAssignObserver::DATA_CODE)
            ->getPoNumber();

        if ($poNumber) {
            $observer->getPaymentModel()->setPoNumber($poNumber);
        }
    }
}
