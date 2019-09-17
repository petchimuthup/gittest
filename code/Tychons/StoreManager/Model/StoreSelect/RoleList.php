<?php

namespace Tychons\StoreManager\Model\Company;

class RoleList implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('Admin')],
            ['value' => 2, 'label' => __('Manager')],
            ['value' => 3, 'label' => __('Employee')]
        ];
    }
}