define([
    'ko',
    'uiComponent',
    'underscore',
    'Magento_Checkout/js/model/step-navigator',
    'jquery',
    'Magento_Customer/js/customer-data',
    'Magento_Customer/js/model/customer',
    'Magento_Customer/js/action/login',
    'mage/validation',
    'Magento_Checkout/js/model/authentication-messages',
    'Magento_Checkout/js/model/full-screen-loader',
], function (
    ko,
    Component,
    _,
    stepNavigator,
    $,
    customerData,
    customer,
    loginAction,
    validation,
    messageContainer,
    fullScreenLoader
) {
    'use strict';

    var checkoutConfig = window.checkoutConfig;

    /**
     * mystep - is the name of the component's .html template,
     * <Vendor>_<Module>  - is the name of your module directory.
     */
    return Component.extend({
        defaults: {
            isGuestCheckoutAllowed: checkoutConfig.isGuestCheckoutAllowed,
            isCustomerLoginRequired: checkoutConfig.isCustomerLoginRequired,
            registerUrl: checkoutConfig.registerUrl,
            forgotPasswordUrl: checkoutConfig.forgotPasswordUrl,
            autocomplete: checkoutConfig.autocomplete,
            template: 'Magenest_CustomCheckout/login-step'
        },

        /**
         * Check if customer is logged in
         *
         * @return {boolean}
         */
        isLoggedIn: function () {
            return customer.isLoggedIn();
        },

        // add here your logic to display step,
        isVisible: ko.observable(false),

        /**
         * @returns {*}
         */
        initialize: function () {
            this._super();

            if (!this.isLoggedIn()) {
                this.isVisible(true);
                // register your step
                stepNavigator.registerStep(
                    // step code will be used as step content id in the component template
                    'login',
                    // step alias
                    null,
                    // step title value
                    'Login',
                    // observable property with logic when display step or hide step
                    this.isVisible,

                    _.bind(this.navigate, this),

                    /**
                     * sort order value
                     * 'sort order value' < 10: step displays before shipping step;
                     * 10 < 'sort order value' < 20 : step displays between shipping and payment step
                     * 'sort order value' > 20 : step displays after payment step
                     */
                    0
                );
            }

            return this;
        },

        /**
         * The navigate() method is responsible for navigation between checkout steps
         * during checkout. You can add custom logic, for example some conditions
         * for switching to your custom step
         * When the user navigates to the custom step via url anchor or back button we_must show step manually here
         */
        navigate: function () {
            this.isVisible(false);
        },

        /**
         * Provide login action.
         *
         * @param {HTMLElement} loginForm
         */
        login: function (loginForm) {
            var loginData = {},
                formDataArray = $(loginForm).serializeArray();

            formDataArray.forEach(function (entry) {
                loginData[entry.name] = entry.value;
            });

            if ($(loginForm).validation() &&
                $(loginForm).validation('isValid')
            ) {
                fullScreenLoader.startLoader();
                loginAction(loginData, checkoutConfig.checkoutUrl, undefined, messageContainer).always(function () {
                    fullScreenLoader.stopLoader();
                });
            }
        }
    });
});
