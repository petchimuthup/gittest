<?php

namespace Tychons\Favoriteorder\Controller\Favorite;

class Index extends \Magento\Framework\App\Action\Action
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
     * favoriteOrderFactory
     *
     * @var \Tychons\Favoriteorder\Model\FavoriteOrderFactory
     */
    protected $favoriteOrderFactory;

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
        \Tychons\Favoriteorder\Model\FavoriteOrderFactory $FavoriteOrderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    )
    {
        $this->storeManager = $storeManager;
        $this->storeList = $storeList;
        $this->jsonFactory = $jsonFactory;
        $this->favoriteOrderFactory = $FavoriteOrderFactory;
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

        $productid = $this->getRequest()->getParam('product_id');

        // Set qty

        $qty = $this->getRequest()->getParam('qty');
        $postQty = $this->getRequest()->getPostValue('qty');
        if ($postQty !== null && $qty !== $postQty) {
            $qty = $postQty;
        }
        if (is_array($qty)) {
            if (isset($qty[$itemId])) {
                $qty = $qty[$itemId];
            } else {
                $qty = 1;
            }
        }


        $productIdqty[] = compact("productid", "qty");

        $productData = json_encode($productIdqty, true);

        $favoriteOrders = $this->favoriteOrderFactory->create()->getCollection()
            ->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('userstore_id', $userStoreId);

        $records = $favoriteOrders->getSize();

        if ($records > 0) {

            foreach ($favoriteOrders as $favoriteOrder) {
                //update qty if same product added
                $getProduct = json_decode($favoriteOrder->getProductIdQty(), true);

                $chekProduct = $this->searchProduct($getProduct, 'productid', $productid);

                if ($chekProduct) {

                    foreach ($getProduct as $key => $data) {
                        if ($data['productid'] == $productid) {

                            $totalQty = $qty + $data['qty'];

                            $getProduct[$key]["qty"] = $totalQty;

                        }
                    }

                    $getProduct = json_encode($getProduct, true);

                } else {

                    //update array if new product added
                    $getProduct = array_merge($getProduct, $productIdqty);

                    $getProduct = json_encode($getProduct, true);

                }

                $id = $favoriteOrder->getEntityId();

                $favoriteOrder->setEntityId($id);

                $favoriteOrder->setCustomerId($customerId);

                $favoriteOrder->setRoleId($userRoleId);

                $favoriteOrder->setUserstoreId($userStoreId);

                $favoriteOrder->setProductIdQty($getProduct);

                $favoriteOrder->setStoreId($storeId);

                $favoriteOrder->setWebsiteId($websiteId);

                $favoriteOrder->save();

                $data['status'] = 1;

                return $result->setData($data);
            }

        } else {

            $favoriteOrder = $this->favoriteOrderFactory->create();

            $favoriteOrder->setCustomerId($customerId);

            $favoriteOrder->setRoleId($userRoleId);

            $favoriteOrder->setUserstoreId($userStoreId);

            $favoriteOrder->setProductIdQty($productData);

            $favoriteOrder->setStoreId($storeId);

            $favoriteOrder->setWebsiteId($websiteId);

            $favoriteOrder->save();

            $data['status'] = 1;

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
