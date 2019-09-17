<?php

namespace Tychons\Storeorder\Block\Product;

class View extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Tychons\Storeorder\Block\StoreOrderProduct
     */
    protected $_storeorderProduct;

    /**
     * ListProduct constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Tychons\Storeorder\Block\StoreOrderProduct $storeorderProduct,
        \Magento\Framework\Registry $registry
    ) {
        $this->_registry = $registry;
        $this->_storeorderProduct = $storeorderProduct;
        parent::__construct($context);
    }

    /**
     * Get post parameters
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getProductId()
    {        
        return $this->_registry->registry('current_product')->getId();
    }

    /**
     * Get post parameters
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getProductName()
    {        
        return $this->_registry->registry('current_product')->getName();
    }

    /**
     * Get store order page url
     *
     * @return string
     */    
    public function getStoreOrderUrl()
    {
        return $this->getUrl('storeorder/index/index', ['_secure' => true]);
    }


    /**
     * Get store order product
     *
     * @return string
     */    
    public function getOrderedProduct()
    {
        return $this->_storeorderProduct->getOrderedProduct();
    }

}

