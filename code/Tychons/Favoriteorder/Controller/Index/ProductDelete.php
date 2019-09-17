<?php

namespace Tychons\Favoriteorder\Controller\Index;

class ProductDelete extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */

    protected $jsonFactory;

    /**
     * favoriteorderFactory
     *
     * @var \Tychons\Favoriteorder\Model\FavoriteOrderFactory
     */

    protected $favoriteOrderFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Magento\Catalog\Model\Product $product ,
     * @param Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface ,
     * @param Magento\Quote\Api\CartManagementInterface $cartManagementInterface ,
     * @param \Magento\Catalog\Helper\ImageFactory $imageHelperFactory
     * @param \Magento\Catalog\Block\Product\ListProduct $listBlock
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Tychons\Favoriteorder\Model\FavoriteOrderFactory $FavoriteOrderFactory,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    )
    {
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
        $itemId = $this->getRequest()->getParam('id');

        $productId = $this->getRequest()->getParam('product_id');

        $result = $this->jsonFactory->create();

        if ($this->getRequest()->getParam('isAjax')) {

            if ($itemId) {

                try {

                    $item = $this->favoriteOrderFactory->create()->load($itemId);

                    $productData = json_decode($item->getProductIdQty(), true);

                    foreach ($productData as $key => $product) {

                        if ($product['productid'] == $productId) {
                            unset($productData[$key]);
                        }
                    }

                    $productData = array_values($productData);

                    $decode = json_encode($productData, true);

                    $item->setProductIdQty($decode)->save();

                    $data['status'] = "Product has been deleted successfully";

                } catch (\Exception $e) {

                    $data['status'] = "Somting went wrong!";
                }

                $result->setData($data);

                return $result;
            }
        }
    }

}
