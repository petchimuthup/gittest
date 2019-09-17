<?php

namespace Tychons\Storeorder\Controller\Index;

class OrderCreate extends \Magento\Framework\App\Action\Action
{

    /**
     * Logging instance
     * @var \Tychons\Storeorder\Logger\Logger
     */
    protected $_logger;

    /**
     * store manager
     * @var Magento\Store\Model\StoreManagerInterface
     */

    protected $_storeManager;

    /**
     * Product instance
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * cart instance
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */

    protected $cartRepositoryInterface;

    /**
     * cartmanagement instance
     * @var \Magento\Quote\Api\CartManagementInterface
     */

    protected $cartManagementInterface;

    /**
     * customer instance
     * @var \Magento\Customer\Model\CustomerFactory
     */

    protected $customerFactory;

    /**
     * customerFactory instance
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */

    protected $customerRepository;

    /**
     * Logging instance
     * @var \Magento\Sales\Model\Order
     */

    protected $order;

    /**
     * Logging instance
     * @var \Tychons\Storeorder\Model\SchedulerFactory
     */

    protected $schedulerFactory;

    /**
     * storeorderFactory
     *
     * @var \Tychons\Storeorder\Model\StoreOrderFactory
     */

    protected $storeOrderFactory;

    /**
     * @var \Magento\Company\Model\CompanyFactory
     */
    protected $companyFactory;

    /**
     * @var \Magento\Wishlist\Model\Wishlist
     */
    protected $storeProduct;

    /**
     * @var \ISN\CompanyExt\Model\CompanyISNFactory
     */
    protected $CompanyisnFactory;

   /**
    * Constructor
    *
    * @param \Magento\Framework\App\Action\Context  $context
    * @param Tychons\Storeorder\Logger\Logger $logger
    * @param Magento\Store\Model\StoreManagerInterface $storeManager
    * @param Magento\Catalog\Model\Product $product,
    * @param Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface,
    * @param Magento\Quote\Api\CartManagementInterface $cartManagementInterface,
    * @param Magento\Customer\Model\CustomerFactory $customerFactory,
    * @param Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
    * @param Magento\Sales\Model\Order $order
    */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Tychons\Storeorder\Logger\Logger $logger,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface,
        \Magento\Quote\Api\CartManagementInterface $cartManagementInterface,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerFactory,
        \Tychons\Storeorder\Model\SchedulerFactory $SchedulerFactory,
        \Tychons\Storeorder\Model\StoreOrderFactory $StoreOrderFactory,
        \Magento\Company\Model\CompanyFactory $companyFactory,
        \Magento\Wishlist\Model\Wishlist $storeProduct,
        \ISN\CompanyExt\Model\CompanyISNFactory $CompanyisnFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Sales\Model\Order $order
    )
    {
        $this->_logger = $logger;
        $this->_storeManager = $storeManager;
        $this->_productFactory = $productFactory;
        $this->companyFactory  = $companyFactory;
        $this->CompanyisnFactory  = $CompanyisnFactory;
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->cartManagementInterface = $cartManagementInterface;
        $this->customerFactory = $customerFactory;
        $this->schedulerFactory = $SchedulerFactory;
        $this->storeOrderFactory = $StoreOrderFactory;
        $this->customerRepository = $customerRepository;
        $this->order = $order;
        parent::__construct($context);
    }

/**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        //get website 
        $websiteId = $this->getWebsiteId();

        echo "Time : ".date("H:i");

        echo "<br/>";

        echo "day : ".date("l");

        die();

        //get store scheduled time
        $schedulerDetails = $this->getStoreScheduler();

        if(empty($schedulerDetails) || count($schedulerDetails) == 0){

            $this->_logger->info('No store schedules at this time');

            return false;
        }

        foreach ($schedulerDetails as $key => $scheduler) 
        {
            //store scheduled time
            $schedulerTime = $scheduler->getTime();

            //scheduled store id
            $storeId = $scheduler->getUserstoreId();

            //magestore id

            $magstoreId = $scheduler->getStoreId();

            //get storeorder details by store id
            $storeOrder = $this->getStoreOrderInfo($storeId);

            if (count($storeOrder) == false) {
                
                $this->_logger->info('Product not available for store id '.$storeId);

            }

            foreach ($storeOrder as $key => $storeProducts)
            {
                //userid/customerId 
                $customerId = $storeProducts->getCustomerId();

                //get user/customer info
                $customerData = $this->getCustomerInfo($customerId);

                //get store address by store id
                $StoreAddress = $this->getStoreAddress($customerData,$storeId);

                //get store 
                $magestore = $this->getStore($magstoreId);

                //create fresh cart for user
                $cartId = $this->createCart($StoreAddress,$magestore,$customerId,$storeId);

                //create order for stororder user
                if (!empty($cartId)) {
                    
                    $orderId = $this->createOrder($customerId,$storeId,$cartId);
                }
            }
        }
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
     * get store
     *
     * @return $this
     */

    public function getStore($storeId)
    {

        return $this->_storeManager->getStore($storeId);
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
     * create cart for customer
     *
     * @return $this
     */

    public function createCart($StoreAddress,$store,$customerId,$userStoreId)
    {
        //Create empty cart
        $cartId = $this->cartManagementInterface->createEmptyCart();

        // load empty cart quote
        $quote = $this->cartRepositoryInterface->get($cartId);

        //set store
        $quote->setStore($store);

        // if you have allready buyer id then you can load customer directly 
        $customer = $this->customerRepository->getById($customerId);

        $quote->setCurrency();

        //Assign quote to customer
        $quote->assignCustomer($customer);

        //add items in quote
        $this->addStoreProduct($customerId,$userStoreId,$quote);

        //Set Address to quote
        $quote->getBillingAddress()->addData($StoreAddress);
        $quote->getShippingAddress()->addData($StoreAddress);

        // Collect Rates and Set Shipping & Payment Method

        $shippingAddress = $quote->getShippingAddress();

        //shipping method
        $shippingAddress->setCollectShippingRates(true)
                        ->collectShippingRates()
                        ->setShippingMethod('freeshipping_freeshipping');
        //payment method 
        $quote->setPaymentMethod('checkmo');

        //not effetc inventory
        $quote->setInventoryProcessed(false);

        // Set Sales Order Payment
        $quote->getPayment()->importData(['method' => 'checkmo']);

        //Now Save quote and your quote is ready
        $quote->save(); 

        // Collect Totals
        $quote->collectTotals();

        if($quote->getId())
        {

            $quoteId = $quote->getId();

            $this->_logger->info('Quote created successfully and quote id is'.$quoteId);

            return $quoteId;

        }else{

            $this->_logger->info('Quote not created,something went wrong!');

            return;
        }
 
    }

    /**
     * create order for customer
     *
     * @return $this
     */

    public function createOrder($cusromerId,$userStore,$cartId)
    {

        $productData = $this->ProductData($cusromerId,$userStore);
        // Create Order From Quote
        $quote = $this->cartRepositoryInterface->get($cartId);

        $orderId = $this->cartManagementInterface->placeOrder($cartId);

        $order = $this->order->load($orderId);

        //get customer id

        $customerNumber = $this->getCustomerNumber($userStore);
       
        $order->setCustomerNumber($customerNumber);

        $order->setCompanyId($userStore);

        $order->setEmailSent(0);

        //set order type
        $order->setOrderStore(1);

        //set po number for item

        $orderItems = $order->getAllItems();

        foreach ($productData as $item) 
        {

            $items = json_decode($item->getProductIdQty(),true);

            foreach ($items as $key => $product) 
            {

                foreach ($orderItems as $orderProduct) 
                {
                    if ($product['productid'] == $orderProduct->getProductId()) 
                    {
                        if(!isset($product['po_number'])){

                            $product['po_number'] = NULL;
                        }

                         $orderProduct->setPoNumber($product['po_number']);

                         $orderProduct->save();
                    }
                }
            }
        }

        $order->save();

        if($order->getEntityId())
        {
            //set status for added product

            foreach ($productData as $storeOrder) 
            {
                 $storeOrder->delete();
            }

            $this->_logger->info('Order created successfully and order id is'.$orderId);

            return $order->getRealOrderId();

        }else{

            $this->_logger->info('Order not created,something went wrong!');
        }
 
    }

    /**
     * add store product to cart
     *
     * @return $this
     */

    public function addStoreProduct($cusromerId,$userStore,$quote)
    {

        $productData = $this->ProductData($cusromerId,$userStore);

        foreach ($productData as $item) 
        {

            $items = json_decode($item->getProductIdQty(),true);

            foreach ($items as $key => $product) 
            {

                $products = $this->_productFactory->create()->load($product['productid']);

                if ($products->getId()) 
                {
                    $quote->addProduct(
                        $products,
                        intval($product['qty'])
                    );
                }
            }
        }
    }

    public function ProductData($cusromerId,$userStore)
    {

        return $this->storeOrderFactory->create()->getCollection()
                                ->addFieldToFilter('customer_id',$cusromerId)
                                ->addFieldToFilter('userstore_id',$userStore);

    }


    /**
     * get scheduler details
     *
     * @return $this
     */

    public function getStoreScheduler()
    {

        $currentTime = date("H:i");

        $currentday = date("l");

        $currentday = "%".$currentday."%";

        return $this->schedulerFactory->create()->getCollection()
                                      ->addFieldToFilter('time', array('lteq' => $currentTime))
                                      ->addFieldToFilter('days_week', array('like' => $currentday));
    }

    /**
     * get scheduler details
     *
     * @return $this
     */

    public function getStoreAddress($customer,$storeId)
    {

        if(empty($storeId)){

            return;
        }

        $address = [];

        $company = $this->companyFactory->create()->load($storeId);

        $address['firstname'] = $customer->getFirstName();

        $address['lastname'] = $customer->getLastName();

        $address['companyName'] = $company->getCompanyName();

        $address['street'] = $company->getStreet();

        $address['city'] = $company->getCity();

        $address['country_id'] = $company->getCountryId();

        $address['region_id'] = $company->getRegionId();

        $address['region'] = $company->getRegion();

        $address['postcode'] = $company->getPostCode();

        $address['telephone'] = $company->getTelePhone();

        $address['save_in_address_book'] = 1;
        
        return $address;
    }

    /**
     * get scheduler details
     *
     * @return $this
     */

    public function getStoreOrderInfo($storeId)
    {

        if(empty($storeId)){

            return;
        }
        return $this->storeOrderFactory->create()->getCollection()
                                        ->addFieldToFilter('userstore_id',$storeId);

    }

    /**
     * get customer number
     *
     * @return $this
     */

    public function getCustomerNumber($companyId)
    {

        if(empty($companyId)){

            return;
        }

        $customerNumber = $this->CompanyisnFactory->create()->load($companyId,"company_id")->getCustomerNumber();

        if (empty($customerNumber)) {
            
            $customerNumber = "";
        }

        return $customerNumber;
    }

}
