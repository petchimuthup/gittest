<?php


namespace Tychons\StoreManager\Controller\Store;

class Delete extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */

    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */

    protected $jsonFactory;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;


    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->storeManager  = $storeManager;
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        $userId = $this->getRequest()->getParam('user_id');

        $storeId = $this->getRequest()->getParam('store_id');

        $websiteId = $this->getWebsiteId();

        $result = $this->jsonFactory->create();

        if ($userId) {
            try {

                //get present store id
                $customerModel = $this->customerFactory->create();

                $customerModel->load($userId);

                $presentStore = $customerModel->getUserstoreId();

                $presentStore = explode(",", $presentStore);

                if (($key = array_search($storeId, $presentStore)) !== false) 
                {
                    unset($presentStore[$key]);
                }

                $presentStore = implode(",", $presentStore);

                //store end 

                //update customer
                $customer = $this->customerRepository->getById($userId);

                if($customer->getId())
                {
                    $customer->setWebsiteId($websiteId);
                    $customer->setCustomAttribute('userstore_id', $presentStore);
                    $this->customerRepository->save($customer);

                }
                $data['status'] = "User has been deleted successfully";
            } catch (\Exception $e) {
               
               $data['status'] = "Can't delete the user";
            }
        }

        $result->setData($data);
        
        return $result;
    }

    /**
     * Get website identifier
     *
     * @return string|int|null
     */
    public function getWebsiteId()
    {
        return $this->storeManager->getStore()->getWebsiteId();
    }
}
