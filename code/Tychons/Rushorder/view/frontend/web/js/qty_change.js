define([
    'jquery',
    'ko',
    'uiComponent'
], function ($, ko, Component) {
    'use strict';

    return Component.extend({
        initialize: function () {
            //initialize parent Component
            this._super();
            this.qty = ko.observable(this.defaultQty);
        },

        decreaseQty: function () {
            var newQty = (parseInt(jQuery(jQuery(event.target).closest(".control")).find(".qty").val()) - 1);
            // var newQty = this.qty()-1;
            if (newQty < 1) {
                newQty = 1;
            }
            //this.qty(newQty);
            (jQuery(jQuery(event.target).closest(".control")).find(".qty").val(newQty));
        },

        increaseQty: function (data, event) {
            var newQty = (parseInt(jQuery(jQuery(event.target).closest(".control")).find(".qty").val()) + 1);

            //   var newQty = this.qty()+1;
            //this.qty(newQty);

            (jQuery(jQuery(event.target).closest(".control")).find(".qty").val(newQty));

            $(".qty").trigger('change');
        }


    });
});