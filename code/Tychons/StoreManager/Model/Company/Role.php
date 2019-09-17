<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tychons\StoreManager\Model\Company;

class Role implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var null|array
     */
    protected $options;

    /**
     * @return array|null
     */
    public function toOptionArray()
    {
        if (null == $this->options) {
            $this->options = [
                ['value' => '1', 'label' => __('Admin')],
                ['value' => '2', 'label' => __('Manager')],
                ['value' => '3', 'label' => __('Employee')]
            ];
        }
        return $this->options;
    }
}
