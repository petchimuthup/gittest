<?php

namespace Tychons\Storeorder\Controller\Store;

class MoveAllToRush extends \Magento\Framework\App\Action\Action
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
     * @var \Tychons\Storeorder\Model\StoreOrderFactory
     */
    protected $StoreOrderFactory;

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
        \Magento\Catalog\Model\ProductFactory $product,
        \Magento\Checkout\Model\Cart $cart,
        \Tychons\Storeorder\Model\StoreOrderFactory $StoreOrderFactory,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    )
    {
        $this->formKey = $formKey;
        $this->cart = $cart;
        $this->product = $product;
        $this->storeOrderFactory = $StoreOrderFactory;    
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

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $itemId = $this->getRequest()->getParam('id');

        $cusromerId = $this->getRequest()->getParam('customer_id');

        $customer_store = $this->getRequest()->getParam('customer_store');

        $productCollection = $this->storeOrderFactory->create()->getCollection()
                                    ->addFieldToFilter('customer_id',$cusromerId)
                                    ->addFieldToFilter('userstore_id',$customer_store);

        if(count($productCollection)>0)
        {

            foreach ($productCollection as $item) 
            {

                $items = json_decode($item->getProductIdQty(),true);

                foreach ($items as $key => $product) 
                {
                    $params = array('form_key' => $this->formKey->getFormKey(),'product' => $product['productid'],'qty'=>$product['qty']);

                    $products = $this->product->create()->load($product['productid']);

                    if ($products->getId()) 
                    {
                        $this->cart->addProduct($products,$params);
                    }
                }

                $cart = $this->cart->save();

                $delete = $item->delete();

                if ($cart && $delete) {
                    
                    $this->messageManager->addSuccess(__('Product has been moved to Rush Order'));

                    return $resultRedirect->setPath('storeorder/index/index');

                }else{

                    $this->messageManager->addErrorMessage('somthing went wrong while moving product');

                    return $resultRedirect->setPath('storeorder/index/index');
                }
            }
        }       
    }
}
