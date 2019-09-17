<?php

namespace Tychons\Favoriteorder\Model;

use Magento\Cron\Exception;

class FavoriteOrder extends \Magento\Framework\Model\AbstractModel
{

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $_dateTime;

    protected $_eventPrefix = 'favoriteorder_details';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Tychons\Favoriteorder\Model\ResourceModel\FavoriteOrder::class);
    }
}