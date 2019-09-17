/**
 * Copyright © ISN, LLC. All rights reserved.
 * Modified to hide edit add address 201907ish
 */

define([
    'underscore',
    'ko',
    'mageUtils',
    'uiComponent',
    'uiLayout',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/model/address-list'
], function (_, ko, utils, Component, layout, quote, addressList) {
    'use strict';

    var defaultRendererTemplate = {
        parent: '${ $.$data.parentName }',
        name: '${ $.$data.name }',
        component: 'ISN_EZGCheckout/js/view/shipping-address/address-renderer/default'
    };

    return Component.extend({
        defaults: {
            template: 'ISN_EZGCheckout/shipping-address/list',
            visible: addressList().length > 0,
            rendererTemplates: []
        },

        /** @inheritdoc */
        initialize: function () {
            this._super().initChildren();

            addressList.subscribe(function (changes) {
                    var self = this;

                    changes.forEach(function (change) {
                        if (change.status === 'added') {
                            self.createRendererComponent(change.value, change.index);
                        }
                    });
                },
                this,
                'arrayChange'
            );

            return this;
        },

        /** @inheritdoc */
        initConfig: function () {
            this._super();
            // the list of child components that are responsible for address rendering
            this.rendererComponents = [];

            return this;
        },

        /** @inheritdoc */
        initChildren: function () {
            var shippingAddress = null;
            addressList().every(function(address){
                if ( address.isDefaultShipping()*1 == 1){
                   // alert("isDefaultShipping");
                    shippingAddress = address;
                    return false;
                }

/*              var shippingAddress = quote.shippingAddress();
                if (shippingAddress) {
                    isSelected = shippingAddress.getKey() == this.address().getKey(); //eslint-disable-line eqeqeq
                }

                if (isSelected){
                    return  this.createRendererComponent(address, 0);
                }
 */
            });

            if (shippingAddress != null) {
                return this.createRendererComponent(shippingAddress, 0);
            }
            return this.createRendererComponent(addressList()[0], 0);;
        },

        /**
         * Create new component that will render given address in the address list
         *
         * @param {Object} address
         * @param {*} index
         */
        createRendererComponent: function (address, index) {
            var rendererTemplate, templateData, rendererComponent;

            if (index in this.rendererComponents) {
                this.rendererComponents[index].address(address);
            } else {
                // rendererTemplates are provided via layout
                rendererTemplate = address.getType() != undefined && this.rendererTemplates[address.getType()] != undefined ? //eslint-disable-line
                    utils.extend({}, defaultRendererTemplate, this.rendererTemplates[address.getType()]) :
                    defaultRendererTemplate;
                templateData = {
                    parentName: this.name,
                    name: index
                };
                rendererComponent = utils.template(rendererTemplate, templateData);
                utils.extend(rendererComponent, {
                    address: ko.observable(address)
                });
                layout([rendererComponent]);
                this.rendererComponents[index] = rendererComponent;
            }
        }
    });
});
