<?php

namespace Tychons\StoreManager\Model\Customer\Attribute\Source;

class RoleId extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [
                ['value' => '1', 'label' => __('Admin')],
                ['value' => '2', 'label' => __('Manager')],
                ['value' => '3', 'label' => __('Employee')]
            ];
        }
        return $this->_options;
    }
}