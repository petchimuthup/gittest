<?php
namespace ISN\CompanyExt\Model\ResourceModel;
class CompanyISN extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    protected function _construct() {
        $this->_init('company_isn','company_isn_id');
    }
}
