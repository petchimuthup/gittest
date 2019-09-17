<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tychons\StoreManager\Ui\Component;

use Magento\Customer\Api\Data\AttributeMetadataInterface;
use Magento\Customer\Ui\Component\Listing\AttributeRepository;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;

class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * @var AttributeRepository
     */
    private $attributeRepository;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
    */

    protected $customerCollection;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
    */

    protected $customerRepository;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Reporting $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param AttributeRepository $attributeRepository
     * @param array $meta
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Reporting $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        AttributeRepository $attributeRepository,
        array $meta = [],
        array $data = []
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->customerCollection = $customerCollectionFactory;
        $this->customerRepository = $customerRepositoryInterface;
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {

        $data = parent::getData();

        foreach ($this->attributeRepository->getList() as $attributeCode => $attributeData) {

            foreach ($data['items'] as &$item) {

                //krishna added this

                $customerId = $item['entity_id'];

                $getStoreId = $this->userStoreId($customerId);

                if(empty($getStoreId) || !isset($getStoreId)){

                    $getStoreId = 0;
                }

                $item['id_store'] = explode(',', $getStoreId);

                //krishna ends this

                if (isset($item[$attributeCode]) && !empty($attributeData[AttributeMetadataInterface::OPTIONS])) {
                    $item[$attributeCode] = explode(',', $item[$attributeCode]);
                }
            }
        }

        //krishna added this

        $storeId = $this->currentStoreId();

        foreach ($data['items'] as $key => $user) 
        {

            if (in_array(0, $user['id_store'])) {
                
                unset($data['items'][$key]);
            }
        
            if(!in_array($storeId, $user['id_store']) || count($user['id_store']) == 0)
            {
                unset($data['items'][$key]);
            }

            $data['items'] = array_values($data['items']);

            $data['totalRecords'] = count($data['items']);
        }

        return $data;
    }

    //krishna added this 

    public function userStoreId($customerId)
    {
         $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

         $customer = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);

         return $customer->getUserstoreId();

    }

    public function currentStoreId()
    {
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $session = $objectManager->get("Magento\Framework\Session\SessionManagerInterface");

        $getStoreId = $session->getUserStoreId();

        if(!empty($getStoreId)){

            return $getStoreId;

        }else{

            return;
        }

    }

}
