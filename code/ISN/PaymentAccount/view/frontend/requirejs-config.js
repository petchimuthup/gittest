/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    config: {
        mixins: {
            'mage/validation': {
                'ISN_PaymentAccount/js/validation': true
            },
            'Magento_Tax/js/view/checkout/summary/grand-total': {
                'ISN_PaymentAccount/js/view/checkout/summary/grand-total': true
            }
        }
    }
};
