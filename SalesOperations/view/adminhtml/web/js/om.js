define([
    'jquery',
    'Magento_Ui/js/modal/confirm',
    'Magento_Ui/js/modal/modal',
    'mage/dataPost',
    'mageUtils',
    'underscore',
    'mage/template',
    'text!Magenest_SalesOperations/template/confirm.html',
    'text!Magenest_SalesOperations/template/cancel.html',
    'jquery/ui',
    'mage/validation',
    'mage/mage',
    'mage/backend/form',
    'mage/calendar',
    'jquery/validate'
], function ($, confirm, modal, dataPost, utils, _, mageTemplate, confirmTemplate, cancelTemplate) {
    'use strict';

    $.widget('mage.om', {
        options: {
            confirmDialog: null,
            cancelDialog: null,
            storeDialog: null,
            confirmForm: null,
            storeForm: null,
            cancelForm: null
        },

        /**
         * @private
         */
        _create: function () {
            var self = this;
            this._super();

            return this;
        },

        /**
         * Show confirm dialog
         *
         * @param message
         * @param action
         * @param saleAgent
         * @param orderSource
         */
        showConfirmOrderDialog: function (message, action, saleAgent, orderSource) {
            saleAgent = JSON.parse(saleAgent);
            orderSource = JSON.parse(orderSource);
            var self = this;
            var content = mageTemplate(confirmTemplate, {saleAgent: saleAgent, orderSource: orderSource});
            if (!this.options.confirmDialog) {
                this.options.confirmDialog = modal({
                    title: $.mage.__(message),
                    innerScroll: true,
                    modalClass: '_image-box',
                    buttons: [
                        {
                            text: $.mage.__('Cancel'),
                            class: 'action action-hide-popup action-dismiss',
                            click: function () {
                                this.closeModal();
                            }
                        },
                        {
                            text: $.mage.__('Confirm'),
                            class: 'action primary action-accept',
                            click: function () {
                                if (self.options.confirmForm.validation() && self.options.confirmForm.validation('isValid')) {
                                    $('body').trigger('processStart');
                                    self.options.confirmForm.submit();
                                    this.closeModal();
                                }
                            }
                        }
                    ],
                    opened: function () {
                        self.options.confirmForm = $('#om-confirm-form').attr('action', action);
                    },
                    clickableOverlay: true
                }, $('<div/>').html(content));
            }

            this.options.confirmDialog.openModal();
        },

        /**
         * Show confirm dialog
         *
         * @param message
         * @param action
         * @param cancelReason
         */
        showConfirmCancelDialog: function (message, action, cancelReason) {
            cancelReason = JSON.parse(cancelReason);
            var self = this;
            var content = mageTemplate(cancelTemplate, {cancelReason: cancelReason});
            if (!this.options.confirmDialog) {
                this.options.confirmDialog = modal({
                    title: $.mage.__(message),
                    innerScroll: true,
                    modalClass: '_image-box',
                    buttons: [
                        {
                            text: $.mage.__('Cancel'),
                            class: 'action action-hide-popup action-dismiss',
                            click: function () {
                                this.closeModal();
                            }
                        },
                        {
                            text: $.mage.__('Confirm'),
                            class: 'action primary action-accept',
                            click: function () {
                                if (self.options.confirmForm.validation() && self.options.confirmForm.validation('isValid')) {
                                    $('body').trigger('processStart');
                                    self.options.confirmForm.submit();
                                    this.closeModal();
                                }
                            }
                        }
                    ],
                    opened: function () {
                        self.options.confirmForm = $('#om-confirm-form').attr('action', action);
                    },
                    clickableOverlay: true
                }, $('<div/>').html(content));
            }

            this.options.confirmDialog.openModal();
        }
    });

    return $.mage.om;
});
