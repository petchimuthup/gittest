<?php

namespace Tychons\StoreManager\Controller\Adminhtml\Store;

class AdminUser extends \Magento\Backend\App\Action
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
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */

    protected $encryptor;
    /**
     * @var \Magento\Customer\Model\SessionFactory
     */

    protected $_customerSession;

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
        \Magento\Framework\Encryption\EncryptorInterface $encrypter,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->storeManager  = $storeManager;
        $this->customerRepository = $customerRepository;
        $this->_customerSession = $customerSession->create();
        $this->encryptor = $encrypter;
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
        
        $result = $this->jsonFactory->create();

        $data = $this->getRequest()->getPostValue();

        //start create customer

        $email = $this->getRequest()->getParam('email');

        $firstname = $this->getRequest()->getParam('firstname');

        $lastname = $this->getRequest()->getParam('lastname');
        //get store id
        $storeId = $this->currentStoreId();

        $userRole = $this->getRequest()->getParam('role');

        if(empty($userRole)){

            $userRole = 1;
        }

        $userStatus = $this->getRequest()->getParam('status');

        $password = $this->getRequest()->getParam('password');

        $conf_password = $this->getRequest()->getParam('conf_password');
        // Get Website ID
        $websiteId  = $this->storeManager->getWebsite()->getWebsiteId();

        //create magento customer

        $customer = $this->customerFactory->create();

        $customer->setWebsiteId($websiteId);

        $customer_check = $customer->setWebsiteId($websiteId)->loadByEmail($email);

        $customer_id = $customer_check->getId();

        if (!$customer_id) 
        {
            // Preparing data for new customer
            $customer->setEmail($email); 
            $customer->setFirstname($firstname);
            $customer->setLastname($lastname);
            $customer->setPassword($password);
            $customer->setConfPassword($conf_password);
            $customer->setUserstoreId($storeId);
            $customer->setRoleId($userRole);
            $customer->setStatus($userStatus);

            try {

                $customer->save();

                return $result->setData('User created successfully!.');

            } catch (LocalizedException $e) {

                return $result->setData($e->getMessage());

            } catch (\Exception $e) {

                return $result->setData('zxxzxzx Went Wrong.');
                
            }

        }else{

            //update customer
            $updateCustomer = $this->customerRepository->getById($customer_id);

            if($updateCustomer->getId())
            {
                $updateCustomer->setWebsiteId($websiteId);
                $updateCustomer->setEmail($email); 
                $updateCustomer->setFirstname($firstname);
                $updateCustomer->setLastname($lastname);
                $updateCustomer->setCustomAttribute('userstore_id', $storeId);
                $updateCustomer->setCustomAttribute('role_id', $userRole);
                $updateCustomer->setCustomAttribute('password', $password);
                $updateCustomer->setCustomAttribute('conf_password', $conf_password);
                
                try {

                    $this->customerRepository->save($updateCustomer);

                    return $result->setData('User updated successfully!.');

                } catch (LocalizedException $e) {

                    return $result->setData($e->getMessage());

                } catch (\Exception $e) {

                    return $result->setData('Something Went Wrong.');   
                }
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
}
