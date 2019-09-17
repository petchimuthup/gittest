<?php

namespace Tychons\Storeorder\Controller\Index;

class QtyUpdate extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */

    protected $jsonFactory;

    /**
     * storeorderFactory
     *
     * @var \Tychons\Storeorder\Model\StoreOrderFactory
     */

    protected $storeOrderFactory;

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
        \Tychons\Storeorder\Model\StoreOrderFactory $StoreOrderFactory,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    )
    {
        $this->jsonFactory = $jsonFactory;
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
        $itemId = $this->getRequest()->getParam('id');

        $productId = $this->getRequest()->getParam('product_id');

        $productQty = $this->getRequest()->getParam('product_qty');

        $poNumber = $this->getRequest()->getParam('pon');

        if(empty($poNumber)){

            $poNumber = "";
        }

        $result = $this->jsonFactory->create();

        if ($this->getRequest()->getParam('isAjax')) 
        {

            if ($itemId)
            {

                try {

                    $item = $this->storeOrderFactory->create()->load($itemId);

                    $productData = json_decode($item->getProductIdQty(),true);

                    foreach ($productData as $key => &$product) 
                    {
                        
                        if($product["productid"] == $productId)
                        {
                            $productData[$key]["qty"] = $productQty;

                            $product['po_number'] = $poNumber;
                        }
                    }

                    $productData = array_values($productData);

                    $decode = json_encode($productData,true);

                    $item->setProductIdQty($decode)->save();

                    $data['status'] = "Product has been updated";

                } catch (\Exception $e) {
                   
                   $data['status'] = "Somting went wrong!";
                }

                $result->setData($data);
                
                return $result;
            }
        }
    }

}
