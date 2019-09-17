<?php


namespace Tychons\StoreManager\Controller\Store;

class User extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;

    /**
     * @var \Tychons\StoreManager\Block\User\Store
     */

    protected $_session;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $session,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_session = $session;
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

        if($this->_session->isLoggedIn()) 
        {
            $customerId = $this->_session->getCustomerId();

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            $customer = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);

            $roleId = $customer->getRoleId();

            if ($roleId == 3) 
            {
                $this->messageManager->addErrorMessage(__('You don\'t have an access to this page, Please Contact Store Admin'));

                return $resultRedirect->setPath('customer/account/login/');
            }

        }else{
            
            $this->messageManager->addErrorMessage(__('Please login to access your Store'));

            return $resultRedirect->setPath('customer/account/login/');
        }

        return $this->resultPageFactory->create();
    }
}
