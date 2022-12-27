define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (Component, rendererList) {
        'use strict';

        if (window.checkoutConfig.payment.coins.enabled) {
            rendererList.push(
                {
                    type: 'coins',
                    component: 'Dorn_Loyalty/js/view/payment/method-renderer/coins-method'
                }
            );
        }

        return Component.extend({});
    }
);