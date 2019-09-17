<?php

namespace Tychons\StoreManager\Block\User;


class Store extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Magento\Customer\Model\SessionFactory
     */

    protected $_customerSession;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
    */

    protected $customerFactory;


    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */

    protected $_encryptor;


    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Magento\Company\Api\CompanyManagementInterface $companyManagement,
        array $data = []
    ) {
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->_encryptor = $encryptor;
        $this->_customerSession = $customerSession->create();
        parent::__construct($context, $data);
    }


    public function getUsers()
    {

        $customer_id = $this->getCustomerId();

        $store_id = $this->userStoreId();

        $store_id = explode(",", $store_id);

        $userCollection = $this->getUserCollection();

        foreach ($userCollection as $key => &$customer) 
        {
            /*$Customerstatus = $this->customerRepository->getById($customer_id);
            $getAttr = $Customerstatus->getExtensionAttributes()->getCompanyAttributes();

            $status = $getAttr->getStatus();*/

            $cusArr = $customer->getUserstoreId();

            $explode = explode(",", trim($cusArr));

            $customer['userstore_id'] = $explode;

        }

        $filter_customer = [];

        foreach ($userCollection as $key => &$customer) 
        {

            $customer = $customer->getData();

            $filter_customer[] = $customer;
            
        }

        $filterUser = $this->getUserLists($filter_customer,$store_id);

        return $filterUser;

    }

    public function getUserLists($customerData,$storeId)
    {

        foreach ($customerData as $key => $user) 
        {

            $userstoreId = $user['userstore_id'];

            $checkuser = array_intersect($userstoreId,$storeId);

            if(count($checkuser) == "" || count($checkuser) == 0)
            {
                unset($customerData[$key]);

            }

            $data['totalRecords'] = count($customerData);
        }

        $customerData = array_values($customerData);

        //map user based on store

        $companyList = $this->userStoreList();

        foreach ($companyList as $key => &$store) 
        {
            $compId = $store['entity_id'];

            foreach ($customerData as $keys => &$user) 
            {
                if(!isset($user['password']) && !isset($user['conf_password']))
                {
                    $user['password'] = NULL;

                    $user['conf_password'] = NULL;
                }
                if(in_array($compId, $user['userstore_id']))
                {
                    $companyList[$key]['user'][] = $customerData[$keys];
                }
            }
        }

        //map ends

        return $companyList;
    }

    public function getCustomerId()
    {

        $customer_data = $this->_customerSession->getCustomerData();

        $customerId = $customer_data->getId();

        if (empty($customerId)) {
            
            return $customerId = "";
            
        }else{

            return $customerId;
        }

        
    }


    public function userStoreId()
    {

        $customerId = $this->getCustomerId();

        if (empty($customerId)) {
           
           return;
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customer = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);

        return $customer->getUserstoreId();

    }

    public function getUserCollection()
    {
        $customerCollection = $this->customerFactory->create();

        $customerCollection->addAttributeToSelect('role_id');

        $customerCollection->addAttributeToSelect('userstore_id');

        $customerCollection->addAttributeToSelect('password');

        $customerCollection->addAttributeToSelect('conf_password');

        return $customerCollection;
    }

    public function getUserRole()
    {

        $customerId = $this->getCustomerId();

        if (empty($customerId)) {
           
           return;
        }

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customer = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);

        return $customer->getRoleId();

    }

    public function userStoreList()
    {

        $storeId = $this->userStoreId();

        if(empty($storeId)) {
           
           return;
        }

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');
        
        $connection= $objectManager->getConnection();

        $usertable = $objectManager->getTableName('company');

        $selectStore = "SELECT * FROM ".$usertable." WHERE entity_id IN(".$storeId.")";

        return $connection->fetchAll($selectStore);
    }

}
