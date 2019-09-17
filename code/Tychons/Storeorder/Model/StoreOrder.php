<?php

namespace Tychons\Storeorder\Model;

use Magento\Cron\Exception;

class StoreOrder extends \Magento\Framework\Model\AbstractModel
{

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $_dateTime;

    protected $_eventPrefix = 'storeorder_details';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Tychons\Storeorder\Model\ResourceModel\StoreOrder::class);
    }
}