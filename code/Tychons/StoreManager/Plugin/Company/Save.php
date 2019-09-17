<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tychons\StoreManager\Plugin\Company;

/**
 * Class Dataprovider
 */
class Save
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
    public function afterExecute(
        \Magento\Company\Controller\Adminhtml\Index\Save $subject,
        $result
    ) {
        $post = $subject->getRequest()->getPostValue();

        $companIsnData = $post['customerinformation'];

        if($companIsnData){

            $company = $this->companyisnFactory->create()->setData($companIsnData)->save();
        }

       return $result;
    }
}
