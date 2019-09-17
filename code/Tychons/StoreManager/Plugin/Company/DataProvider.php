<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tychons\StoreManager\Plugin\Company;

/**
 * Class Dataprovider
 */
class DataProvider
{
	 /**
     * @var \ISN\CompanyExt\Model\CompanyISNFactory
     */
    private $companyisnFactory;

    /**
     * DataProvider constructor
     *
     * @param \ISN\CompanyExt\Model\CompanyISNFactory $companyisnFactory
     */
    public function __construct(
        \ISN\CompanyExt\Model\CompanyISNFactory $companyisnFactory
    ) {
        $this->companyisnFactory = $companyisnFactory;
    }
    /**
     * @param \Magento\Company\Model\Company\DataProvider $subject
     * @param array $result
     *
     * @return array
     */
    public function afterGetData(
        \Magento\Company\Model\Company\DataProvider $subject,
        $result
    ) {

		$companyId = key($result);

    	$companyisnData = $this->companyisnFactory->create()->load($companyId,"company_id")->getData();

    	unset($companyisnData['website_id']);

    	//print_r($companyisnData);

        foreach ($result as $key => &$data) 
        {

        	$data['customerinformation'] = $companyisnData;
        }

       return $result;
    }
}
