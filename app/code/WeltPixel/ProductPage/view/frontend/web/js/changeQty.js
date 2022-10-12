define([
    'ko',
    'uiComponent',
    'jquery'
], function (ko, Component, $) {
    'use strict';
    return Component.extend({
        initialize: function () {
            this._super();
            this.qty = ko.observable($(this.qtyInput).val() * 1);
            this.maxQtyValue = $(this.qtyInput).attr('max');
        },
        decreaseQty: function() {
            var newQty = this.qty() - 1;
            if (newQty < 1)
            {
                newQty = 1;
            }
            this.qty(newQty);
        },
        increaseQty: function() {
            var newQty = this.qty() + 1;
            if (newQty <= this.maxQtyValue) {
                this.qty(newQty);
            } else {

            }
        }
    });

});
