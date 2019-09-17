<?php
namespace ISN\Checkout\Plugin\Model\Checkout;

use Magento\Quote\Api\Data\PaymentInterface;

class PaymentInformationManagement {
    private $logger;
   
    /** @var \Magento\Sales\Model\Order\Status\HistoryFactory */
    protected $historyFactory;
    
    /** @var \Magento\Sales\Model\OrderFactory */
    protected $orderFactory;
    
    /** @var \Magento\Framework\Json\Helper\Data */
    protected $_jsonHelper;
    
    /** @var \Magento\Framework\Filter\FilterManager */
    protected $_filterManager;
    
    
    public function __construct(\Magento\Framework\Json\Helper\Data $jsonHelper,
                                \Magento\Framework\Filter\FilterManager $filterManager,
                                \Magento\Sales\Model\OrderFactory $orderFactory) {

        $this->orderFactory = $orderFactory;
        $this->_jsonHelper = $jsonHelper;
        $this->_filterManager = $filterManager;
    }

    
    public function aroundSavePaymentInformationAndPlaceOrder(
        \Magento\Checkout\Model\PaymentInformationManagement $subject,
        \Closure $proceed,
        $cartid,
        PaymentInterface $paymentMethod,
        \magento\Quote\Api\Data\Addressinterface $billingAddress = null){
        $poNumber = null;
        $poComments = null;
            
        // execute the original function and get the result
        $orderId = $proceed($cartid, $paymentMethod, $billingAddress);
        $order = $this->orderFactory->create()->load($orderId);
        if($order->getEntityId()){
            $requestBody = file_get_contents('php://input');
            $data = $this->_jsonHelper->jsonDecode($requestBody);

            if(isset($data['poComments'])){
                if($data['poComments']){
                    $poComments = $this->_filterManager->stripTags($data['poComments']);
                    $order->addCommentToStatusHistory($poComments, false, true);
                }
            }

            if(isset($data['poNumber'])){
                if($data['poNumber']){
                    $poNumber = $this->_filterManager->stripTags($data['poNumber']);
                
                    /** @var $payment \Magento\Sales\Model\Order\Payment */
                    $payment = $order->getPayment();
                    $payment->setPoNumber($poNumber);
                    $payment->save();
                }
            }
            
            $order->save();
        }
        
        return $orderId;
    }
}