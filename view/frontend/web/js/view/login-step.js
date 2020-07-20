define(
    [
        'jquery',
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'Team23_LoginStep/js/action/login',
        'Magento_Checkout/js/action/get-payment-information',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Customer/js/customer-data',
        'mage/translate',
        'mage/validation'
    ],
    function (
        $,
        ko,
        Component,
        _,
        stepNavigator,
        loginAction,
        getPaymentInformationAction,
        fullScreenLoader,
        customerData,
        $t
    ) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Team23_LoginStep/login-step',
                isLoggedIn: ko.observable(false)
            },

            //add here your logic to display step,
            isVisible: ko.observable(true),
            isLoading: ko.observable(false),
            isLoggedIn: ko.observable(false),
            isSendButtonVisible: ko.observable(false),
            sortOrder: 1,

            /**
             *
             * @returns {*}
             */
            initialize: function () {
                this._super();

                // register the login-step
                stepNavigator.registerStep(
                    'login-step',
                    'login-step',
                    $t('Login or Register'),
                    this.isVisible,

                    _.bind(this.navigate, this),

                    this.sortOrder
                );

                return this;
            },

            /**
             * Responsible for navigation between checkout step during checkout.
             *
             * @returns void
             */
            navigate: function () {
                this.isVisible(true);
            },

            /**
             * Navigate to next step (i.e. shipping)
             *
             * @returns void
             */
            navigateToNextStep: function () {
                fullScreenLoader.startLoader();
                var deferred = $.Deferred();

                getPaymentInformationAction(deferred);
                stepNavigator.next(deferred);
                $.when(deferred).done(function () {
                    fullScreenLoader.stopLoader();
                });
            },

            isGuestShippingAllowed: function() {
                return !!window.checkoutConfig.isGuestCheckoutAllowed;
            }
        });
    }
);
