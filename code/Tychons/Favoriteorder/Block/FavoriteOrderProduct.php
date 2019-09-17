<?php

namespace Tychons\Favoriteorder\Block;

class FavoriteOrderProduct extends \Magento\Framework\View\Element\Template
{


    /**
     * favoriteProduct
     *
     * @var \Tychons\Favoriteorder\Controller\Index\Index
     */

    protected $favoriteProduct;

    /**
     * addedProduct
     *
     * @var \Tychons\Favoriteorder\Modal\FavoriteorderFactory
     */

    protected $addedProductFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Tychons\Favoriteorder\Model\FavoriteOrderFactory $FavoriteOrderFactory,
        \Tychons\Favoriteorder\Controller\Index\Index $favoriteProduct,
        array $data = []
    )
    {

        $this->favoriteProduct = $favoriteProduct;
        $this->addedProductFactory = $FavoriteOrderFactory;
        parent::__construct($context, $data);
    }


    /**
     * get scheduler details
     *
     * @return $this
     */

    public function getFavoriteProduct()
    {
        return $this->favoriteProduct->getFavoriteProduct();
    }

    /**
     * get getPresentRole
     *
     * @return $int|null
     */

    public function getActiveUserRole()
    {

        return $this->favoriteProduct->userRole();
    }

    /**
     * get customer id
     *
     * @return $int|null
     */

    public function getLoggedUserProduct()
    {

        return $this->favoriteProduct->getLoggedUserProduct();

    }

    /**
     * get addedproduct
     *
     * @return $this
     */

    public function getAddedProduct()
    {

        $customerId = $this->getCustomerId();

        echo $userStoreId = $this->getPresentStoreId();

        die();

        return $this->addedProduct->create()->getCollection()
            ->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('userstore_id', $userStoreId);

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
