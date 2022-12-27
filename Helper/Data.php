<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Helper;

use Dorn\Loyalty\Api\CoinsTransactionRepositoryInterface;
use Dorn\Loyalty\Api\Data\CoinsTransactionInterface;
use Dorn\Loyalty\Model\ResourceModel\CoinsTransaction\CollectionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Customer\Model\CustomerIdProvider;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Data extends AbstractHelper
{
    private const XML_PATH_MODULE_ENABLE = 'loyalty/general/enable';
    private const XML_PATH_ENABLE_PDP_MESSAGE = 'loyalty/general/enable_pdp_message';
    private const XML_PATH_COINSBACK_PERCENT = 'loyalty/general/coins_back_percent';

    private ?CoinsTransactionInterface $loadedTransaction = null;

    public function __construct(
        Context $context,
        private CustomerRepositoryInterface $customerRepository,
        private CoinsTransactionRepositoryInterface $coinsTransactionRepository,
        private CurrentCustomer $currentCustomer,
        private CollectionFactory $collectionFactory,
        private \Magento\Framework\Pricing\Helper\Data $priceHelper,
        private CustomerIdProvider $customerIdProvider
    ) {
        parent::__construct($context);
    }

    public function isModuleEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_MODULE_ENABLE);
    }

    public function shouldDisplayCoinsBackMessage(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLE_PDP_MESSAGE)
            && $this->scopeConfig->isSetFlag(self::XML_PATH_MODULE_ENABLE);
    }

    public function calculateCoinsBackAmount(float $amount): float
    {
        $coinsBackValue = $this->scopeConfig->getValue(self::XML_PATH_COINSBACK_PERCENT) / 100;

        return $amount * $coinsBackValue;
    }

    public function getCoinsTransaction(): ?CoinsTransactionInterface
    {
        if (! $this->loadedTransaction instanceof CoinsTransactionInterface) {
            try {
                $this->loadedTransaction = $this->coinsTransactionRepository->getById(
                    (int) $this->_getRequest()->getParam('id')
                );
            } catch (NoSuchEntityException) {
                return null;
            }
        }

        return $this->loadedTransaction;
    }

    /**
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\InputException
     */
    public function updateCoinsReceivedValue(
        CoinsTransactionInterface $transaction,
        CustomerInterface $customer,
        array $data
    ): void {
        $transaction->setCoinsReceived((float) $data['coins_received']);
        if ($transaction->dataHasChangedFor(CoinsTransactionInterface::COINS_RECEIVED)) {
            $transaction->setAddedByAdmin(true);

            $currentCustomerCoins = $customer->getCustomAttribute('coins')?->getValue() ?? 0;
            $oldCoinsReceivedValue = $transaction->getOrigData(CoinsTransactionInterface::COINS_RECEIVED);
            $newCoinsReceivedValue = $data['coins_received'];
            $diff = $newCoinsReceivedValue - $oldCoinsReceivedValue;

            $customer->setCustomAttribute('coins', $currentCustomerCoins + $diff);

            $this->coinsTransactionRepository->save($transaction);
            $this->customerRepository->save($customer);
        }
    }

    public function getList(): array
    {
        $customerId = $this->currentCustomer->getCustomerId();
        if ($customerId === null) {
            return [];
        }
        /** @var \Dorn\Loyalty\Model\ResourceModel\CoinsTransaction\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter(CoinsTransactionInterface::CUSTOMER_ID, $customerId);
        $items = $collection->getItems();

        return array_map(function ($item) {
            /** @var CoinsTransactionInterface $item */
            return [
                'occasion'         => $item->getAddedByAdmin() ? __('Added by admin') : $item->getOrderId(),
                'amountOfPurchase' => $this->priceHelper->currency($item->getAmountOfPurchase()),
                'coinsReceived'    => $item->getCoinsReceived(),
                'coinsSpend'       => $item->getCoinsSpend(),
                'dateOfPurchase'   => date('M d, Y h:i:s A', strtotime($item->getDateOfPurchase()))
            ];
        }, $items);
    }

    public function getCurrentCustomerTotalCoins(): float
    {
        if ($this->currentCustomer->getCustomerId()) {
            $result = (float) $this->currentCustomer->getCustomer()->getCustomAttribute('coins')?->getValue();
        } else {
            try {
                $customer = $this->customerRepository->getById($this->customerIdProvider->getCustomerId());
                $result = (float) $customer->getCustomAttribute('coins')?->getValue();
            } catch (NoSuchEntityException|LocalizedException) {
                $result = null;
            }
        }

        return $result ?? 0.0;
    }
}