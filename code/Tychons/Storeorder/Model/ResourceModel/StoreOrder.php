<?php

namespace Tychons\Storeorder\Model\ResourceModel;

class StoreOrder extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('storeorder_details', 'entity_id');
    }
}
