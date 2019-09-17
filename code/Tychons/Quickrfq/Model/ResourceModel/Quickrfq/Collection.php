<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tychons\Quickrfq\Model\ResourceModel\Quickrfq;

use Tychons\Quickrfq\Model\ResourceModel\AbstractCollection;

/**
 * CMS page collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'quickrfq_id';

    public function addStoreFilter($store, $withAdmin = true)
    {
        return $this;
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Tychons\Quickrfq\Model\Quickrfq', 'Tychons\Quickrfq\Model\ResourceModel\Quickrfq');
        $this->_map['fields']['quickrfq_id'] = 'main_table.quickrfq_id';
    }
}
