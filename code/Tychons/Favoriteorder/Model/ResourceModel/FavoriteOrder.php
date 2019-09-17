<?php

namespace Tychons\Favoriteorder\Model\ResourceModel;

class FavoriteOrder extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('favoriteorder_details', 'entity_id');
    }
}
