<?php

namespace Tychons\StoreManager\Controller\Store;

class StoreSelect extends \Magento\Framework\App\Action\Action
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
     * @var Tychons\StoreManager\Model\StoreSelectFactory
     */
    protected $storeSelectFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

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
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Tychons\StoreManager\Model\StoreSelectFactory $storeSelectFactory,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->storeManager  = $storeManager;
        $this->storeSelectFactory = $storeSelectFactory;
        $this->_customerSession = $customerSession;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        $storeId = $this->getRequest()->getParam('store_id');

        $result = $this->jsonFactory->create();

        if ($storeId) 
        {
            try {

                $userId = $this->getCustomerId();

                $websiteId = $this->getWebsiteId();

                $mag_storeId = $this->getStoreId();

                //update customer
                $activeStore = $this->storeSelectFactory->create()->load($userId,'customer_id');

                if($activeStore->getEntityId())
                {

                    $id = $activeStore->getEntityId();

                    $data['entity_id'] = $id;

                    $activeStore->setEntityId($id)
                                ->setUserstoreId($storeId)
                                ->setCustomerId($userId)
                                ->setStoreId($mag_storeId)
                                ->setWebsiteId($websiteId)
                                ->save();

                    $data['status'] = 1;

                }else{

                    $data['status'] = "fails";
                }
                
            } catch (\Exception $e) {
               
               $data['status'] = "fails";
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

    /**
     * Get store identifier
     *
     * @return string|int|null
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
    /**
     * Get website identifier
     *
     * @return string|int|null
     */
    public function getCustomerId()
    {

        $customer_data = $this->_customerSession->create()->getCustomerData();

        $customer_data->getId();

        return $customer_data->getId();
    }
}
