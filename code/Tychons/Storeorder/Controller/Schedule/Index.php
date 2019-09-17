<?php


namespace Tychons\Storeorder\Controller\Schedule;

class Index extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;

    /**
     * schedulerFactory
     *
     * @var \Tychons\Storeorder\Model\SchedulerFactory
     */

    protected $schedulerFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Tychons\StoreManager\Block\StoreList
     */
    protected $activeStore;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Tychons\Storeorder\Model\SchedulerFactory $SchedulerFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Tychons\StoreManager\Block\StoreList $activeStore,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->schedulerFactory = $SchedulerFactory;
        $this->storeManager  = $storeManager;
        $this->activeStore  = $activeStore;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $scheduler = (array) $this->getRequest()->getPost();

        $activeStore = $this->getActiveStoreId();

        $scheduler['userstore_id'] = $activeStore;

        $scheduler['store_id'] = $this->getStoreId();

        $scheduler['website_id'] = $this->getWebsiteId();

        if(isset($scheduler['days_week']) && isset($scheduler['time'])){

            $week_days = $this->getRequest()->getParam('days_week');

            if (is_array($week_days)) {
                
                $week_days = implode(",", $week_days);

                $scheduler['days_week'] = $week_days;
            }

            $time = $this->getRequest()->getParam('est_time');

            $schedulerFactory = $this->schedulerFactory->create()->load($activeStore,'userstore_id');

            if($schedulerFactory->getSchedulerId()){

                $id = $schedulerFactory->getSchedulerId();

                $scheduler['scheduler_id'] = $id;

                $schedulerFactory->setData($scheduler)->save();

                $this->messageManager->addSuccessMessage(__('Schedule updated successfully!'));

                return $resultRedirect->setPath('*/*/index');

            }else{


                $schedulerFactory->setData($scheduler)->save();

                $this->messageManager->addSuccessMessage(__('Schedule saved successfully!'));

                return $resultRedirect->setPath('*/*/index');

            }
           
               
               //$this->messageManager->addErrorMessage(__('Somthing went wrong!'));

               //return $resultRedirect->setPath('*/*/index');

        }

        return $this->resultPageFactory->create();
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
     * Get active store identifier
     *
     * @return string|int|null
     */
    public function getActiveStoreId()
    {
        return $this->activeStore->getActiveStoreId();
    }

    /**
     * Get active store identifier
     *
     * @return string|int|null
     */
    public function getActiveStoreSchedule()
    {
        $activeStore = $this->getActiveStoreId();

        return $this->schedulerFactory->create()->load($activeStore,'userstore_id')->getData();
    }
}
