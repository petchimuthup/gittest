/*global define*/
define([
    'Magento_Ui/js/form/form'
], function(Component) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'ISN_Checkout/checkout/form/purchase-order-form'
        },

        initialize: function () {
            this._super();
            // component initialization logic
            return this;
        },

    });
});