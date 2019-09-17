/*global define*/
define([
    'underscore',
    'ko',
    'mageUtils',
    'uiComponent',
    'uiLayout'
], function(_, ko, utils, Component, layout) {
    'use strict';

    var defaultRendererTemplate = {
        component: 'ISN_EZGCheckout/js/view/text'
    };

    return Component.extend({
        defaults: {
            template: 'ISN_EZGCheckout/summary-address/address-item',
            rendererTemplates: []
        },

        /** @inheritdoc */
        initialize: function () {
            this._super();
            return this;
        },

        /** @inheritdoc */
        initConfig: function () {
            this._super();
            // the list of child components that are responsible for address rendering
            this.rendererComponents = [];

            return this;
        }
    });
});
