<?php

namespace ISN\CompanyExt\Model;

class CompanyISN extends \Magento\Framework\Model\AbstractModel implements \ISN\CompanyExt\Api\Data\CompanyISNInterface, \Magento\Framework\DataObject\IdentityInterface {
    const CACHE_TAG = 'company_isn';

    protected function _construct() {
        $this->_init('ISN\CompanyExt\Model\ResourceModel\CompanyISN');
    }

    public function getIdentities() {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
