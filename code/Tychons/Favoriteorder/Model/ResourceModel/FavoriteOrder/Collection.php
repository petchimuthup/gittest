<?php

namespace Tychons\Favoriteorder\Model\ResourceModel\FavoriteOrder;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Tychons\Favoriteorder\Model\FavoriteOrder::class,
            \Tychons\Favoriteorder\Model\ResourceModel\FavoriteOrder::class
        );
    }
}
