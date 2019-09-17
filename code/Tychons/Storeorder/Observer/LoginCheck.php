<?php
namespace Tychons\Storeorder\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class LoginCheck implements ObserverInterface
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;

    /**
     * @var \Magento\Framework\App\Response\Http
     */
    protected $http;

    /** @var \Magento\Customer\Model\Session */
    protected $customerSession;

    /** @var \Magento\Framework\Message\ManagerInterface */
    protected $_messageManager;

    /** @var \Tychons\StoreManager\Block\StoreList */
    protected $activeStore;

    /**
     * @var Tychons\StoreManager\Model\StoreSelectFactory
     */
    protected $storeSelectFactory;

    /**
     * @var Tychons\StoreManager\Model\StoreSelectFactory
     */
    protected $_storeManager;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $redirect;

    /**
     * @var \Magento\Framework\App\ActionFlag
     */
    protected $_actionFlag;

    /**
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Framework\App\Response\Http $http
     * @param \Magento\Framework\App\ActionFlag $actionFlag
     * @param \Magento\Framework\App\Response\RedirectInterface $redirect
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Response\Http $http,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Tychons\StoreManager\Block\StoreList $activeStore,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Framework\App\ActionFlag $actionFlag,
        \Tychons\StoreManager\Model\StoreSelectFactory $storeSelectFactory,
        \Magento\Customer\Model\Session $customerSession
    )
    {
        $this->url = $url;
        $this->http = $http;
        $this->_actionFlag = $actionFlag;
        $this->redirect = $redirect;
        $this->_messageManager = $messageManager;
        $this->activeStore  = $activeStore;
        $this->customerFactory  = $customerFactory;
        $this->_storeManager = $storeManager;
        $this->storeSelectFactory = $storeSelectFactory;
        $this->customerSession = $customerSession;
    }

    /**
     * Manages redirect
     */
    public function execute(Observer $observer)
    {

       $pageName = $observer->getRequest()->getFullActionName();
        
        //restricted page for user if not logged in
        $pageRestrict = array(
	    'askit_customer_index',
	    'customer_account_index',
            'purchasesummary_show_summary',
            'storeorder_schedule_index',
            'company_store_user',
	    'purchasesummary_show_summary',
	    'orderhistory_index_index',
	    'purchaseorderdetails_index_index',
            'cms_page_view',
            'favoriteorder_index_index',
            'quickorder_index_index',
            'checkout_cart_index',
            'storeorder_index_index',
            'cms_index_index',
            'catalog_category_view',
            'customer_account_create',
            'catalogsearch_result_index',
            'customer_account_confirm',
            'customer_account_confirmation',
            'customer_account_forgotpassword',
            'customer_account_forgotpasswordpost',
            'customer_account_createpassword',
            'customer_account_resetpasswordpost',
            'customer_account_logoutSuccess'
        );

        /**
         * Check if user logged in
         */
        if ($this->customerSession->isLoggedIn()) 
        {

            $storeselect = $this->storeSelectFactory->create();

            $activeStore = $this->activeStore->getActiveStoreId();

            $customerId = $this->activeStore->getCustomerId();

            $storeId = $this->getStoreId();

            $websiteId = $this->getWebsiteId();

            //check if user not assigned to any store/company

            $LoggedinStorelist = $this->activeStore->userStoreList();

            if(empty($LoggedinStorelist))
            {

                if(in_array($pageName, $pageRestrict))
                {

                    $this->_messageManager->addErrorMessage('You have not assigned to any store!.Please Contact Store Admin!.');

                    return $this;

                }
            }

            if (empty($activeStore) && !empty($LoggedinStorelist)) 
            {

                $userstoreid = $this->activeStore->userStoreId();

                $userstoreid = current(explode(",", $userstoreid));

                $storeselect->setUserstoreId($userstoreid);

                $storeselect->setCustomerId($customerId);

                $storeselect->setStoreId($storeId);

                $storeselect->setWebsiteId($websiteId);

                $storeselect->save();

            }

            //first lime login redirect to password reset page

            $pageName = $observer->getRequest()->getFullActionName();
            
            $customerReset = $this->customerFactory->create()->load($customerId);

            $resetStatus = $customerReset->getFirsttimeLogin();

            if (!$resetStatus) 
            {
                if(in_array($pageName, $pageRestrict))
               {

                     /** @var \Magento\Framework\App\Action\Action $controller */
                     $controller = $observer->getControllerAction();

                     $this->_messageManager->addErrorMessage('Please reset the password for first time login!');

                     $this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
                     $this->redirect->redirect($controller->getResponse(), 'customer/account/edit/changepass/1');

                }
            }

        }else{

            if(in_array($pageName, $pageRestrict))
            {

                /** @var \Magento\Framework\App\Action\Action $controller */
                $controller = $observer->getControllerAction();

                //$this->http->setRedirect($this->url->getUrl('customer/account/login'), 301);

                 $this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
                 $this->redirect->redirect($controller->getResponse(), 'customer/account/login');
            }

    
        }
    }

    /**
     * Get store identifier
     *
     * @return  int
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
    
    /**
     * Get website identifier
     *
     * @return string|int|null
     */
    public function getWebsiteId()
    {
        return $this->_storeManager->getStore()->getWebsiteId();
    }
}
