<?php

namespace Tychons\StoreManager\Model\ResourceModel;

class StoreSelect extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('user_store_select', 'entity_id');
    }
}
