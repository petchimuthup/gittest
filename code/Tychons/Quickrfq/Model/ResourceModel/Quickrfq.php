<?php

namespace Tychons\Quickrfq\Model\ResourceModel;

class Quickrfq extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{


    protected function _construct()
    {
        $this->_init('tychons_quickrfq', 'quickrfq_id');
    }
}
