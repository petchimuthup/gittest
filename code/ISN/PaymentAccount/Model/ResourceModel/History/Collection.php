<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Model\ResourceModel\History;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * History collection.
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
            \ISN\PaymentAccount\Model\History::class,
            \ISN\PaymentAccount\Model\ResourceModel\History::class
        );
    }
}
