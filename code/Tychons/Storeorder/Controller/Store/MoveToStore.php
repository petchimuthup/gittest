<?php

namespace Tychons\Storeorder\Controller\Store;

class MoveToStore extends \Magento\Framework\App\Action\Action
{

    /**
     * store manager
     * @var Magento\Store\Model\StoreManagerInterface
     */

    protected $storeManager;

    /**
     * store List
     * @var \Tychons\StoreManager\Block\StoreList
     */

    protected $storeList;

    /**
     * storeorderFactory
     *
     * @var \Tychons\Storeorder\Model\StoreOrderFactory
     */
    protected $storeOrderFactory;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */

    protected $jsonFactory;


   /**
    * Constructor
    *
    * @param \Magento\Framework\App\Action\Context $context
    * @param Magento\Store\Model\StoreManagerInterface $storeManager
    */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Tychons\StoreManager\Block\StoreList $storeList,
        \Tychons\Storeorder\Model\StoreOrderFactory $StoreOrderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    )
    {
        $this->storeManager = $storeManager;
        $this->storeList = $storeList;
        $this->checkoutSession = $checkoutSession;
        $this->jsonFactory = $jsonFactory;
        $this->cart = $cart;
        $this->storeOrderFactory = $StoreOrderFactory;
        parent::__construct($context);
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {

        $result = $this->jsonFactory->create();

        //get website 
        $websiteId = $this->getWebsiteId();

        //get store 
        $storeId = $this->getStoreId();

        $customerId = $this->getCustomerId();

        $userStoreId = $this->getPresentStoreId();

        $userRoleId = $this->getRoleId();

        $productid = $this->getRequest()->getParam("product_id");

        $qty = $this->getRequest()->getParam("qty");

        $productIdqty[] = compact("productid","qty");

        $productData = json_encode($productIdqty,true);

        $storeOrders = $this->storeOrderFactory->create()->getCollection()
                                              ->addFieldToFilter('customer_id',$customerId)
                                              ->addFieldToFilter('userstore_id',$userStoreId);

        $records = $storeOrders->getSize();

        if($records >0){

            foreach ($storeOrders as $storeOrder) 
            {
                    //update qty if same product added
                    $getProduct = json_decode($storeOrder->getProductIdQty(),true);

                    $chekProduct = $this->searchProduct($getProduct,'productid',$productid);

                    if ($chekProduct) {

                        foreach ($getProduct as $key => $data) 
                        {
                            if ($data['productid'] == $productid) {

                                $totalQty = $qty+$data['qty'];
                                
                                $getProduct[$key]["qty"] = $totalQty;

                            }
                        }

                        $getProduct = json_encode($getProduct,true);

                    }else{

                        //update array if new produt added
                        $getProduct = array_merge($getProduct,$productIdqty);

                        $getProduct = json_encode($getProduct,true);

                    }

                    $id = $storeOrder->getEntityId();

                    $storeOrder->setEntityId($id);

                    $storeOrder->setCustomerId($customerId);

                    $storeOrder->setRoleId($userRoleId);

                    $storeOrder->setUserstoreId($userStoreId);

                    $storeOrder->setProductIdQty($getProduct);

                    $storeOrder->setStoreId($storeId);

                    $storeOrder->setWebsiteId($websiteId);

                    $storeOrder->save();

                    $data['status'] = 1;

                    //remove product from rush order

                    $allItems = $this->checkoutSession->getQuote()->getAllVisibleItems();

                    foreach ($allItems as $item) 
                    {
                        $productId = $item->getProduct()->getId();

                        if($productId == $productid)
                        {
                            $itemId = $item->getItemId();

                            $this->cart->removeItem($itemId)->save();
                        }

                    }

                    return $result->setData($data);

                    //remove rush order product end
            }

        }else{

            $storeOrder = $this->storeOrderFactory->create();

            $storeOrder->setCustomerId($customerId);

            $storeOrder->setRoleId($userRoleId);

            $storeOrder->setUserstoreId($userStoreId);

            $storeOrder->setProductIdQty($productData);

            $storeOrder->setStoreId($storeId);

            $storeOrder->setWebsiteId($websiteId);

            $storeOrder->save();

            $data['status'] = 1;

            //remove product from rush order

            $allItems = $this->checkoutSession->getQuote()->getAllVisibleItems();

            foreach ($allItems as $item) 
            {
            
                $productId = $item->getProduct()->getId();

                if($productId == $productid)
                {
                    $itemId = $item->getItemId();

                    $this->cart->removeItem($itemId)->save();
                }

            }

            //remove rush order product end

            return $result->setData($data);
                    
        }
    }

    /**
     * get website id
     *
     * @return int|null
     */

    public function getWebsiteId()
    {

        return $this->storeManager->getDefaultStoreView()->getWebsiteId();
    }

    /**
     * get store
     *
     * @return $int|null
     */

    public function getStoreId()
    {

        return $this->storeManager->getStore()->getId();
    }

    /**
     * get customerId
     *
     * @return $int|null
     */

    public function getCustomerId()
    {

        return $this->storeList->getCustomerId();
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
     * get roleId
     *
     * @return $this
     */

    public function getRoleId()
    {

        return $this->storeList->userRole();
    }

    /**
     * search product
     *
     * @return $this
     */

    public function searchProduct($array, $key, $val)
    {
        foreach ($array as $item)
        if (isset($item[$key]) && $item[$key] == $val)
            return true;
         return false;
    }
}
