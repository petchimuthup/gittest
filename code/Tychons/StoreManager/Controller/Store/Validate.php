<?php

namespace Tychons\StoreManager\Controller\Store;

class Validate extends \Magento\Framework\App\Action\Action
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

        $result = $this->jsonFactory->create();

        $email = $this->getRequest()->getParam('email');

        $websiteId = $this->getRequest()->getParam('website_id');

        if ($email) {
            try {

                $customerModel = $this->customerFactory->create();

                $customerModel->setWebsiteId($websiteId); 

                $customerModel->loadByEmail($email);

                $customerId = $customerModel->getId();

                if(!empty($customerId)){

                    $data['exist'] = $customerId;

                }else{

                    $data['notexist'] = "not found";
                }


            } catch (\Exception $e) {
               
               $data['status'] = "somthing went wrong";
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
