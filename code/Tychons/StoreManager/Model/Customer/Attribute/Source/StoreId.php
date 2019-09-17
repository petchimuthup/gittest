<?php

namespace Tychons\StoreManager\Model\Customer\Attribute\Source;

class StoreId extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    protected $_companyList;

    public function __construct(
        \Magento\Company\Model\Company\GetList $companyList
    ) {
        $this->_companyList = $companyList;
    }

    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {

        $companyList = $this->getList();

        if(!count($companyList)){

            $this->_options = ['label' => "", 'value' => ""];
        }

        if ($this->_options === null) {

            foreach ($companyList as $field) {

                $this->_options[] = ['label' => $field['company_name'], 'value' => $field['entity_id']];
            }
        }

        return $this->_options;
    }

    public function getList()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');
    
        $connection= $objectManager->getConnection();

        $usertable = $objectManager->getTableName('company');

        $selectCompany = "SELECT * FROM ".$usertable."";

        $companyRecords = $connection->fetchAll($selectCompany);

        return $companyRecords;
    }

}