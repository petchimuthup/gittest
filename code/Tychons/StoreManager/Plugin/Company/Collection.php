<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tychons\StoreManager\Plugin\Company;

/**
 * Class Collection
 */
class Collection extends \Magento\Company\Model\ResourceModel\Company\Grid\Collection
{
    /**
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->joinCompanyIsnTable();
        return $this;
    }

    protected function joinCompanyIsnTable()
    {

        $this->getSelect()->joinLeft(
        ['company_isn' => $this->getTable('company_isn')],
        'main_table.entity_id = company_isn.company_id',
        ['customer_number','parent_customer_number','po_number_required','credit_allow','account_allow','customer_price_group','customer_group']
        );

        return $this;
    }
}
