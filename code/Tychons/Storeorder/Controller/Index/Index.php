<?php

namespace Tychons\Storeorder\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */

    protected $resultPageFactory;

    /**
     * store manager
     * @var Magento\Store\Model\StoreManagerInterface
     */

    protected $_storeManager;

    /**
     * Product instance
     * @var \Magento\Catalog\Model\Product
     */
    protected $_productFactory;


    /**
     * storeorderFactory
     *
     * @var \Tychons\Storeorder\Model\StoreOrderFactory
     */

    protected $storeOrderFactory;

    /**
     * @var \Tychons\Storeorder\Model\SchedulerFactory
     */
    protected $SchedulerFactory;

    /**
     * store List
     * @var \Tychons\StoreManager\Block\StoreList
     */

    protected $storeList;

    /**
     * image helper
     * @var \Magento\Catalog\Helper\ImageFactory
     */

    protected $imageHelperFactory;

    /**
     * add to cart url
     * @var \Magento\Catalog\Block\Product\ListProduct
     */

    protected $listBlock;

    /**
     * @var \Tychons\StoreManager\Block\User\Store
     */

    protected $_session;

    /**
     * @var \Magento\Directory\Model\CurrencyFactory
     */

    protected $currencysymbol;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */

    protected $priceFormatter;

   /**
    * Constructor
    *
    * @param \Magento\Framework\App\Action\Context  $context
    * @param Tychons\Storeorder\Logger\Logger $logger
    * @param Magento\Store\Model\StoreManagerInterface $storeManager
    * @param Magento\Catalog\Model\Product $product,
    * @param Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface,
    * @param Magento\Quote\Api\CartManagementInterface $cartManagementInterface,
    * @param \Magento\Catalog\Helper\ImageFactory $imageHelperFactory
    * @param \Magento\Catalog\Block\Product\ListProduct $listBlock
    */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Tychons\Storeorder\Model\SchedulerFactory $SchedulerFactory,
        \Tychons\StoreManager\Block\StoreList $storeList,
        \Tychons\Storeorder\Model\StoreOrderFactory $StoreOrderFactory,
        \Magento\Catalog\Helper\ImageFactory $imageHelperFactory,
        \Magento\Customer\Model\Session $session,
        \Magento\Directory\Model\CurrencyFactory $currencysymbol,
        \Magento\Framework\Pricing\Helper\Data $priceFormatter,
        \Magento\Catalog\Block\Product\ListProduct $listBlock,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_storeManager = $storeManager;
        $this->_productFactory = $productFactory;
        $this->schedulerFactory = $SchedulerFactory;
        $this->currencysymbol = $currencysymbol;
        $this->priceFormatter = $priceFormatter;
        $this->storeOrderFactory = $StoreOrderFactory;
        $this->storeList = $storeList;
        $this->_session = $session;
        $this->imageHelperFactory = $imageHelperFactory;
        $this->listBlock = $listBlock;
        parent::__construct($context);
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if(!$this->_session->isLoggedIn()) 
        {
            $this->messageManager->addErrorMessage(__('Please login to access your Store'));

            return $resultRedirect->setPath('customer/account/login/');
        }

        return  $this->resultPageFactory->create();
    }

    /**
     * get website id
     *
     * @return int|null
     */

    public function getWebsiteId()
    {

        return $this->_storeManager->getDefaultStoreView()->getWebsiteId();
    }

    /**
     * get currency symbol
     *
     * @return int|null
     */

    public function getCurrencySymbol()
    {

        $currencyCode = $this->_storeManager->getStore()->getCurrentCurrencyCode();

        $symbol = $this->currencysymbol->create()->load($currencyCode);

        return $symbol->getCurrencySymbol();

    }

    /**
     * get store
     *
     * @return $this
     */

    public function getStore()
    {

        return $this->_storeManager->getStore();
    }    

    /**
     * get customer info
     *
     * @return $this
     */

    public function getCustomerInfo($customerId)
    {
        return $this->customerFactory->getById($customerId);;
 
    }


    /**
     * get scheduler details
     *
     * @return $this
     */

    public function getStoreProductInfo($storeId,$customerId)
    {

        if(empty($storeId)){

            return;
        }
        return $this->storeOrderFactory->create()->getCollection()
                    ->addFieldToFilter('userstore_id',$storeId)
                    ->addFieldToFilter('customer_id',array('neq' => $customerId))
                    ->getData();
    }

    /**
     * get logged in user product
     *
     * @return $this
     */

    public function getLoggedUserProduct()
    {
        $activeStoreId = $this->getPresentStoreId();

        $customerId = $this->getCustomerId();
        
        $loggedProduct = $this->storeOrderFactory->create()->getCollection()
                    ->addFieldToFilter('userstore_id',$activeStoreId)
                    ->addFieldToFilter('customer_id',$customerId)
                    ->getData();

        $imageUrl = $this->imageHelperFactory->create();

        foreach ($loggedProduct as &$item) 
        {

            $items = json_decode($item['product_id_qty'],true);

            $item['products'] = $items;

            $item['user_name'] = $this->getCustomerName($item['customer_id']);

            foreach ($item['products'] as $key => &$product) 
            {

                $products = $this->_productFactory->create()->load($product['productid']);

                if ($products->getId()) 
                {
                    $product['sku'] = $products->getSku();
                    $product['name'] = $products->getName();
                    $product['price'] = $this->priceFormatter->currency($products->getFinalPrice());
                    $product['image'] = $imageUrl->init($products, 'category_page_grid')->getUrl();
                    $product['product_url'] =$products->getProductUrl();
                    $product['addtocart'] = $this->listBlock->getAddToCartUrl($products);
                }
            }
        }

        return $loggedProduct;
    }

    /**
     * get getPresentStoreId
     *
     * @return $int|null
     */

    public function getPresentStoreId()
    {

        return $this->storeList->getActiveStoreId();
    }

    /**
     * get getPresentStoreId
     *
     * @return $int|null
     */

    public function userRole()
    {

        return $this->storeList->userRole();
    }

    /**
     * get storeproduct
     *
     * @return $this
     */

    public function getStoreProduct()
    {
        //get website 
        $websiteId = $this->getWebsiteId();

        $activeStoreId = $this->getPresentStoreId();

        $customerId = $this->getCustomerId();

        //get storeorde by store id
        $storeProduct = $this->getStoreProductInfo($activeStoreId,$customerId);

        $imageUrl = $this->imageHelperFactory->create();

        if(count($storeProduct)>0){

        foreach ($storeProduct as &$item) 
        {

            $items = json_decode($item['product_id_qty'],true);

            $item['products'] = $items;

            $item['user_name'] = $this->getCustomerName($item['customer_id']);

            foreach ($item['products'] as $key => &$product) 
            {

                $products = $this->_productFactory->create()->load($product['productid']);

                if ($products->getId()) 
                {
                    $product['sku'] = $products->getSku();
                    $product['name'] = $products->getName();
                    $product['price'] = $this->priceFormatter->currency($products->getFinalPrice());
                    $product['image'] = $imageUrl->init($products, 'category_page_grid')->getUrl();
                    $product['product_url'] =$products->getProductUrl();
                    $product['addtocart'] = $this->listBlock->getAddToCartUrl($products);
                }
            }
        }

    }else{

        $storeProduct = array();
    }

        return $storeProduct;
    }

    /**
     * get getPresentStoreId
     *
     * @return $int|null
     */

    public function getCustomerId()
    {

        return $this->storeList->getCustomerId();
    }

    /**
     * get getcustomer Name
     *
     * @return $this
     */

    public function getCustomerName($id)
    {
        return $this->storeList->getCustomerName($id);
    }

}
