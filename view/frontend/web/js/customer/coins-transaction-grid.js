define(
    [
        'ko',
        'jquery',
        'uiComponent',
        'mage/url',
        'mage/translate'
    ],
    function (
        ko,
        $,
        Component,
        urlBuilder,
        $t
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                "template": "Dorn_Loyalty/customer/coins-transaction-table"
            },

            transactions: ko.observableArray([]),

            totalCoins: ko.observable(0),

            initialize: function () {
                this._super();

                this.loadTransactions();

                this.totalCoinsMsg = ko.pureComputed(() => {
                    return $t('You have %1 coins'.replace('%1', this.currentCoins()));
                });

                return this;
            },

            loadTransactions: function () {
                $.ajax({
                    url: urlBuilder.build("/coins/ajax/getlist"),
                    showLoader: true,
                    success: response => {
                        if (response.success) {
                            this.transactions(response.data);
                            this.totalCoins(response.totalCoins)
                        }
                    }
                });
            }
        });
    });