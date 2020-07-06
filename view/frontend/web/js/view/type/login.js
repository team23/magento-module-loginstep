define([
    'jquery',
    'ko',
    'Magento_Ui/js/form/form',
    'Magento_Customer/js/customer-data',
    'Magento_Customer/js/model/customer',
    'Team23_LoginStep/js/action/login',
    'mage/translate',
    'mage/url',
    'mage/validation'
], function (
        $,
        ko,
        Component,
        customerData,
        customer,
        loginAction,
        $t,
        url
    ) {
    'use strict';

    return Component.extend({
        registerUrl: window.checkoutConfig.login_step.settings.customerRegisterUrl,
        forgotPasswordUrl: window.checkoutConfig.login_step.settings.customerForgotPasswordUrl,
        autocomplete: window.checkoutConfig.login_step.settings.autocomplete,
        loginStepConfig:  window.checkoutConfig.login_step.config,
        modalWindow: null,
        isLoading: ko.observable(false),
        isVisible: ko.observable(true),
        sortOrder: 20,

        defaults: {
            template: 'Team23_LoginStep/type/login',
            isLoggedIn: ko.observable(customer.isLoggedIn()),

            exports: {
                isLoggedIn: 'checkout.steps.login-step:isLoggedIn',
                isLoading: 'checkout.steps.login-step:isLoading'}
        },

        /**
         * Init
         */
        initialize: function () {
            var self = this;

            this._super();

            url.setBaseUrl(window.checkoutConfig.login_step.settings.baseUrl);
            loginAction.registerLoginCallback(function () {
                self.isLoading(false);
            });

            return this;
        },

        /**
         * Provide login action
         *
         * @return {Boolean}
         */
        login: function (formUiElement, event) {
            var loginData = {},
                formElement = $(event.currentTarget),
                formDataArray = formElement.serializeArray();

            event.stopPropagation();
            event.preventDefault();

            formDataArray.forEach(function (entry) {
                loginData[entry.name] = entry.value;
            });

            if (formElement.validation() &&
                formElement.validation('isValid')
            ) {
                this.isLoading(true);
                loginAction(loginData);
            }

            return false;
        },

        getLoginStepOrder: function() {
            return this.loginStepConfig.login_step_order;
        },

        isCustomRegisterMessageEnabled: function() {
            return !!this.loginStepConfig.custom_register;
        },

        isCustomLoginMessageEnabled: function() {
            return !!this.loginStepConfig.custom_login;
        },

        getCustomRegisterMessage: function() {
            return this.loginStepConfig.register_msg;
        },

        getCustomLoginMessage: function() {
            return this.loginStepConfig.login_msg;
        }
    });
});
