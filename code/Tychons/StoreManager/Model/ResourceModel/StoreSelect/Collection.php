<?php

namespace Tychons\StoreManager\Model\ResourceModel\StoreSelect;

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
            \Tychons\StoreManager\Model\StoreSelect::class,
            \Tychons\StoreManager\Model\ResourceModel\StoreSelect::class
        );
    }
}
