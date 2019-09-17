<?php

namespace Tychons\StoreManager\Block;

class StoreList extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
    */

    protected $customerRepository;

    /**
     * @var \Magento\Customer\Model\SessionFactory
    */

    protected $_customerSession;

    /**
     * @var Tychons\StoreManager\Model\StoreSelectFactory
     */
    protected $storeSelectFactory;

    /**
     * @var \Magento\Company\Model\ResourceModel\Company\CollectionFactory
     */
    protected $companyFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Company\Model\ResourceModel\Company\CollectionFactory $companyCollectionFactory,
        \Tychons\StoreManager\Model\StoreSelectFactory $storeSelectFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        array $data = []
    ) {

        $this->_customerSession = $customerSession->create();
        $this->customerRepository = $customerRepositoryInterface;
        $this->storeSelectFactory = $storeSelectFactory;
        $this->companyFactory = $companyCollectionFactory;
        parent::__construct($context, $data);
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

    public function userStoreList()
    {

        $storeId = $this->userStoreId();

        if(empty($storeId)) {
           
           return;
        }

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');
        
        $connection = $objectManager->getConnection();

        $usertable = $objectManager->getTableName('company');

        $selectStore = "SELECT * FROM ".$usertable." WHERE entity_id IN(".$storeId.")";

        return $connection->fetchAll($selectStore);
    }

    public function userRole()
    {

        $customerId = $this->getCustomerId();

        if (empty($customerId)) {
           
           return;
        }

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customer = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);

        return $customer->getRoleId();

    }

    public function getCustomerId()
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customerSession = $objectManager->create('Magento\Customer\Model\Session');

        $customerId = $customerSession->getCustomer()->getId();

        if (!empty($customerId)) {
           
           return $customerId;

        }else{

            return;
        }
    }

    public function getCustomerName($id)
    {

        if (empty($id)) {
           
           return;
        }

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customer = $objectManager->create('Magento\Customer\Model\Customer')->load($id);

        $firstname = $customer->getFirstname();

        $lastname = $customer->getLastname();

        return $firstname.' '.$lastname;
    }

    public function getActiveStoreId()
    {

        $userId = $this->getCustomerId();

        $activeStore = $this->storeSelectFactory->create()->load($userId,'customer_id');

        $userStore = $activeStore->getUserstoreId();

        if (!empty($userStore)) {
           
           return $userStore;

        }else{

            return;
        }
    }


}
