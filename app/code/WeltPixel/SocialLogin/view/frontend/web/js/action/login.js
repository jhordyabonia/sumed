define([
    'jquery',
    'mage/storage',
    'Magento_Ui/js/model/messageList',
    'Magento_Customer/js/customer-data'
], function ($, storage, globalMessageList, customerData) {
    'use strict';

    var callbacks = [],

        /**
         * @param {Object} loginData
         * @param {String} redirectUrl
         * @param {*} isGlobal
         * @param {Object} messageContainer
         */
        action = function (loginData, redirectUrl, isGlobal, messageContainer) {
            messageContainer = messageContainer || globalMessageList;

            return storage.post(
                'sociallogin/ajax/login',
                JSON.stringify(loginData),
                isGlobal
            ).done(function (response) {
                if (response.errors) {
                    messageContainer.addErrorMessage({
                        'message':response.message
                    });
                    callbacks.forEach(function (callback) {
                        callback(loginData);
                    });
                } else {
                    callbacks.forEach(function (callback) {
                        callback(loginData);
                    });
                    customerData.invalidate(['customer']);

                    if (redirectUrl) {
                        window.location.href = BASE_URL+"home-commerce-logged";
                    } else if (response.redirectUrl) {
                        window.location.href = BASE_URL+"home-commerce-logged";
                    } else {
                        window.location.href = BASE_URL+"home-commerce-logged";
                    }
                }
            }).fail(function () {
                messageContainer.addErrorMessage({
                    'message': 'Could not authenticate. Please try again later'
                });
                callbacks.forEach(function (callback) {
                    callback(loginData);
                });
            });
        };

    /**
     * @param {Function} callback
     */
    action.registerLoginCallback = function (callback) {
        callbacks.push(callback);
    };

    return action;
});
