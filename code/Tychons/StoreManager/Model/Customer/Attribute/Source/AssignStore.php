<?php

namespace Tychons\StoreManager\Model\Customer\Attribute\Source;

class AssignStore extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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
                ['value' => (string) 'Admin', 'label' => __('Admin')],
                ['value' => (string) 'Manager', 'label' => __('Manager')],
                ['value' => (string) 'Employee', 'label' => __('Employee')]
            ];
        }
        return $this->_options;
    }
}