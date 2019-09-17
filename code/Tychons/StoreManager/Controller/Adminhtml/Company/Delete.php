<?php

namespace Tychons\StoreManager\Controller\Adminhtml\Company;

class Delete extends \Magento\Backend\App\Action
{

    protected $urlBuilder;

    const URL_PATH_STORE = 'company/index/edit';

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
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->storeManager  = $storeManager;
        $this->urlBuilder = $urlBuilder;
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        parent::__construct($context);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $userId = $this->getRequest()->getParam('id');

        $storeId = $this->currentStoreId();

        $redirectPath = $this->urlBuilder->getUrl('company/index/edit',['id' => $storeId]);

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
                
                $this->messageManager->addSuccessMessage(__('You deleted the User.'));

                return $resultRedirect->setPath($redirectPath);

            } catch (\Exception $e) {
               
                $this->messageManager->addErrorMessage(__('Can\'t delete the user'));

                return $resultRedirect->setPath($redirectPath);
            }
        }        
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