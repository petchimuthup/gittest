define(['Magento_Checkout/js/model/quote',
		'Magento_Checkout/js/model/url-builder',
		'Magento_Customer/js/model/customer',
		'Magento_Checkout/js/model/place-order'
	],
	function (quote, urlBuilder, customer, placeOrderService){
		'use strict';
		
		return function (paymentData, messageContainer){
			var serviceUrl, payload;
			
			payload = {
					cartId: quote.getQuoteId(),
					billingAddress: quote.billingAddress(),
					paymentMethod: paymentData,
					poComments: jQuery('#comments').val(),
					poNumber: jQuery('#poNumber').val()
			};
			
			serviceUrl = urlBuilder.createUrl('/carts/mine/payment-information', {});
			
			return placeOrderService(serviceUrl, payload, messageContainer);
		};	
	}
);