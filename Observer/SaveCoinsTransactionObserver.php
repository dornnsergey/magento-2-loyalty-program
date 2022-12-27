<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Observer;

use Dorn\Loyalty\Api\CoinsTransactionRepositoryInterface;
use Dorn\Loyalty\Helper\Data;
use Dorn\Loyalty\Model\CoinsTransactionFactory;
use Dorn\Loyalty\Model\Gateway\Config\ConfigProvider;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

class SaveCoinsTransactionObserver implements \Magento\Framework\Event\ObserverInterface
{
	public function __construct(
		private readonly Data $helper,
		private readonly CustomerRepositoryInterface $customerRepository,
		private readonly ManagerInterface $message,
		private readonly CoinsTransactionFactory $coinsTransactionFactory,
		private readonly CoinsTransactionRepositoryInterface $coinsTransactionRepository
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function execute(Observer $observer)
	{
		if (! $this->helper->isModuleEnabled()) {
			return;
		}

		/** @var \Magento\Sales\Model\Order\Invoice $invoice */
		$invoice = $observer->getInvoice();
		$customerId = (int) $invoice->getCustomerId();

		try {
			$customer = $this->customerRepository->getById($customerId);
		} catch (NoSuchEntityException|LocalizedException|\Exception) {
			return;
		}

		$paymentMethod = $observer->getPayment()->getMethod();
		$baseGrandTotal = (float) $invoice->getBaseGrandTotal();
		$coinsTransaction = $this->coinsTransactionFactory->create()
			->setOrderId((int) $invoice->getOrderId())
			->setCustomerId($customerId)
			->setAmountOfPurchase($baseGrandTotal)
			->setDateOfPurchase($invoice->getOrder()->getCreatedAt());

		if ($paymentMethod === ConfigProvider::PAYMENT_CODE) {
			$valueFromTransaction = -$baseGrandTotal;
			$coinsTransaction->setCoinsSpend($baseGrandTotal);
		} else {
			$valueFromTransaction = $this->helper->calculateCoinsBackAmount($baseGrandTotal);
			$coinsTransaction->setCoinsReceived($valueFromTransaction);
		}

		$currentCustomerCoins = $customer->getCustomAttribute('coins')?->getValue() ?? 0;
		$currentCustomerCoins += $valueFromTransaction;
		$customer->setCustomAttribute('coins', $currentCustomerCoins);

		try {
			$this->coinsTransactionRepository->save($coinsTransaction);
			$this->customerRepository->save($customer);
		} catch (\Exception) {
			$this->message->addErrorMessage(__('The bonus coins have not been obtained.'));
		}
	}
}