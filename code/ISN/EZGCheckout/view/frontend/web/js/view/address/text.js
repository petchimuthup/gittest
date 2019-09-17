/*global define*/
define([
    'Magento_Ui/js/form/element/text'
], function(Component) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'ISN_EZGCheckout/address/text'
        },

        initialize: function () {
            this._super();
            // component initialization logic
            return this;
        },

    });
});