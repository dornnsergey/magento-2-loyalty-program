<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Observer;

use Dorn\Loyalty\Helper\Data;
use Dorn\Loyalty\Model\CoinsPaymentMethod;
use Dorn\Loyalty\Model\Gateway\Config\ConfigProvider;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;

class CheckCoinsAmountBeforePlaceOrderObserver implements ObserverInterface
{
    public function __construct(
        private CurrentCustomer $currentCustomer,
        private Data $helper
    ) {
    }

    /**
     * @inheritDoc
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(Observer $observer)
    {
        $paymentMethod = $observer->getOrder()->getPayment()->getMethod();
        if ($paymentMethod === ConfigProvider::PAYMENT_CODE) {
            if (! $this->currentCustomer->getCustomerId() || ! $this->helper->isModuleEnabled()) {
                return;
            }

            $customerCoinsAmount = $this->helper->getCurrentCustomerTotalCoins();

            $grandTotal = $observer->getOrder()->getBaseGrandTotal();

            if ($customerCoinsAmount < $grandTotal) {
                throw new LocalizedException(__('You do not have enough coins for this purchase.'));
            }
        }
    }
}