<?php

namespace Tychons\Favoriteorder\Block\Product;

class ListProduct extends \Magento\Catalog\Block\Product\ProductList\Item\Block
{
    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;

    /**
     * addedProduct
     *
     * @var \Tychons\Favoriteorder\Modal\FavoriteorderFactory
     */

    protected $addedProductFactory;

    /**
     * favoriteProduct
     *
     * @var \Tychons\Favoriteorder\Controller\Index\Index
     */

    protected $favoriteProduct;

    /**
     * ListProduct constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Tychons\Favoriteorder\Model\FavoriteOrderFactory $FavoriteOrderFactory,
        \Tychons\Favoriteorder\Controller\Index\Index $favoriteProduct,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        array $data = []
    )
    {
        $this->urlHelper = $urlHelper;
        $this->favoriteProduct = $favoriteProduct;
        $this->addedProductFactory = $FavoriteOrderFactory;
        parent::__construct($context, $data);
    }

    /**
     * Get post parameters
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                'product_name' => $product->getName(),
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }

    /**
     * get addedproduct
     *
     * @return $this
     */

    public function getAddedProduct()
    {

        $customerId = $this->getCustomerId();

        $userStoreId = $this->getPresentStoreId();

        return $this->addedProductFactory->create()->getCollection()
            ->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('userstore_id', $userStoreId)->getData();

    }

    /**
     * get customer id
     *
     * @return $int|null
     */

    public function getCustomerId()
    {

        return $this->favoriteProduct->getCustomerId();

    }

    /**
     * get getPresentStoreId
     *
     * @return $int|null
     */

    public function getPresentStoreId()
    {

        return $this->favoriteProduct->getPresentStoreId();

    }
}

