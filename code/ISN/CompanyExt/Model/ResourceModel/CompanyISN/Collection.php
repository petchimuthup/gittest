<?php
namespace ISN\CompanyExt\Model\ResourceModel\CompanyISN;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
    protected function _construct() {
        $this->_init('ISN\CompanyExt\Model\CompanyISN','ISN\CompanyExt\Model\ResourceModel\CompanyISN');
    }
}