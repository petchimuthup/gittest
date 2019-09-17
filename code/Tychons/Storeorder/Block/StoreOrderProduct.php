<?php

namespace Tychons\Storeorder\Block;

class StoreOrderProduct extends \Magento\Framework\View\Element\Template
{
    /**
     * schedulerFactory
     *
     * @var \Tychons\Storeorder\Controller\Schedule\Index
     */

    protected $scheduler;

    /**
     * storeproduct
     *
     * @var \Tychons\Storeorder\Controller\Index\Index
     */

    protected $storeProduct;

    /**
     * @var \ISN\CompanyExt\Model\CompanyISNFactory
     */
    protected $CompanyisnFactory;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */

    protected $orderFactory;


    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Tychons\Storeorder\Controller\Schedule\Index $Scheduler,
        \Tychons\Storeorder\Controller\Index\Index $storeProduct,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \ISN\CompanyExt\Model\CompanyISNFactory $CompanyisnFactory,
        array $data = []
    ) {
        $this->scheduler = $Scheduler;
        $this->storeProduct = $storeProduct;
        $this->CompanyisnFactory  = $CompanyisnFactory;
        $this->orderFactory = $orderFactory;
        parent::__construct($context, $data);
    }

    /**
     * get scheduler details
     *
     * @return $this
     */

    public function getSchedules()
    {
        return $this->scheduler->getActiveStoreSchedule();
    }

    /**
     * get scheduler details
     *
     * @return $this
     */

    public function getStoreProduct()
    {
        return $this->storeProduct->getStoreProduct();
    }

    /**
     * get getPresentStoreId
     *
     * @return $int|null
     */

    public function getPresentStoreId()
    {

     return $this->storeProduct->getPresentStoreId();

    }

    /**
     * get currency symbols
     *
     * @return $int|null
     */

    public function getCurrencySymbol()
    {

     return $this->storeProduct->getCurrencySymbol();

    }

    /**
     * get getPresentRole
     *
     * @return $int|null
     */

    public function getActiveUserRole()
    {

     return $this->storeProduct->userRole();
    }

    /**
     * get customer id
     *
     * @return $int|null
     */

    public function getCustomerId()
    {

     return $this->storeProduct->getCustomerId();

    }

    /**
     * get customer id
     *
     * @return $int|null
     */

    public function getLoggedUserProduct()
    {

     return $this->storeProduct->getLoggedUserProduct();
     
    }

    public function checkPoRequired()
    {

        $companyId = $this->getPresentStoreId();

        $porequired = $this->CompanyisnFactory->create()->load($companyId,"company_id")->getPoNumberRequired();

        if (empty($porequired)) {
            
            $porequired = "";
        }

        return $porequired;
    }

    /**
     * get ordered product
     *
     * @return $this
     */

    public function getOrderedProduct()
    {

         $customerId = $this->getCustomerId();

         $companyId = $this->getPresentStoreId();

         $collection = $this->orderFactory->create()->getCollection()
                                    ->addFieldToFilter("customer_id", $customerId)
                                    ->addFieldToFilter("company_id", $companyId);

        $products = array();

        $podate = array();

        foreach ($collection as $order) {

        foreach ($order->getAllVisibleItems() as $item) {

            $products[] = $item->getProductId();

            $convert = date('d-m-Y', strtotime($order->getCreatedAt()));

            $podate[] = $convert;
        }

        }
    
        $finaldate = array_combine($products, $podate);

       return $finaldate;
    }

}

