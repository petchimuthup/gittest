<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Model\ResourceModel\CreditLimit;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * CreditLimit collection.
 */
class Collection extends AbstractCollection
{
    /**
     * Standard collection initialization.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \ISN\PaymentAccount\Model\CreditLimit::class,
            \ISN\PaymentAccount\Model\ResourceModel\CreditLimit::class
        );
    }
}
