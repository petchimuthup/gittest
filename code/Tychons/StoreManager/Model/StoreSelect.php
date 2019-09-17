<?php

namespace Tychons\StoreManager\Model;

use Magento\Cron\Exception;

class StoreSelect extends \Magento\Framework\Model\AbstractModel
{

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $_dateTime;

    protected $_eventPrefix = 'user_store_select';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Tychons\StoreManager\Model\ResourceModel\StoreSelect::class);
    }
}