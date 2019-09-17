<?php

namespace Tychons\Favoriteorder\Controller\Favorite;

class MoveToRush extends \Magento\Framework\App\Action\Action
{

    /**
     * store cart
     * @var \Magento\Checkout\Model\Cart
     */

    protected $cart;

    /**
     * store formKey
     * @var \Magento\Framework\Data\Form\FormKey
     */

    protected $formKey;

    /**
     * product
     *
     * @var \Magento\Catalog\Model\Product
     */
    protected $product;

    /**
     * product
     *
     * @var \Tychons\Storeorder\Model\FavoriteOrderFactory
     */
    protected $FavoriteOrderFactory;

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
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Catalog\Model\Product $product,
        \Magento\Checkout\Model\Cart $cart,
        \Tychons\Favoriteorder\Model\FavoriteOrderFactory $FavoriteOrderFactory,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    )
    {
        $this->formKey = $formKey;
        $this->cart = $cart;
        $this->product = $product;
        $this->favoriteOrderFactory = $FavoriteOrderFactory;
        $this->jsonFactory = $jsonFactory;
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

        $itemId = $this->getRequest()->getParam('id');

        $productid = $this->getRequest()->getParam('product_id');


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


        if ($this->getRequest()->getParam('isAjax')) {
            if ($itemId) {

                try {

                    $params = array('form_key' => $this->formKey->getFormKey(), 'product' => $productid, 'qty' => $qty);

                    $product = $this->product->load($productid);

                    $this->cart->addProduct($product, $params);

                    $cart = $this->cart->save();

                    $item = $this->favoriteOrderFactory->create()->load($itemId);

                    $productData = json_decode($item->getProductIdQty(), true);

                    foreach ($productData as $key => $product) {

                        if ($product['productid'] == $productid) {
                            unset($productData[$key]);
                        }
                    }

                    $productData = array_values($productData);

                    $decode = json_encode($productData, true);

                    $item->setProductIdQty($decode)->save();

                    $data['status'] = "Product has been moved successfully";

                } catch (\Exception $e) {

                    $data['status'] = "Somting went wrong!";
                }

                $result->setData($data);

                return $result;
            }
        }
    }
}
