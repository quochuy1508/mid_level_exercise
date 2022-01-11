define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/modal/modal'
], function ($, alert, modal) {
    'use strict';

    return function (Massactions) {
        return Massactions.extend({
            initialize: function () {
                this._super();
                console.log('Hello from the mixin!');
            },

            /**
             * Applies specified action.
             *
             * @param {String} actionIndex - Actions' identifier.
             * @returns {Massactions} Chainable.
             */
            applyAction: function (actionIndex) {
                let that = this;
                if (actionIndex === 'register_event') {
                    that.modalCustom();
                } else {
                    return this._super(actionIndex);
                }
            },

            modalCustom: function () {
                var options = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    modalClass: 'custom-popup-modal',
                    buttons: []
                };
                var popup = modal(options, $('#custom-popup-modal'));

                $( document ).ready(function() {
                    $('#custom-popup-modal').modal('openModal');
                });
            }
        });
    }
});
