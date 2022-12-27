<?php

declare(strict_types=1);

namespace Dorn\Loyalty\ViewModel\Customer;

use Dorn\Loyalty\Api\Data\CoinsTransactionInterface;
use Dorn\Loyalty\Model\ResourceModel\CoinsTransaction\CollectionFactory;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\Data\Collection;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Theme\Block\Html\Pager;

class CoinsList implements ArgumentInterface
{

    public function __construct(
        private readonly CurrentCustomer $currentCustomer,
        private readonly CollectionFactory $collectionFactory,
    ) {
    }

    public function getPager(Collection $collection, Pager $pager): string
    {
        $pager->setCollection($collection);

        return $pager->toHtml();
    }

    public function getCollection(): \Dorn\Loyalty\Model\ResourceModel\CoinsTransaction\Collection
    {
        /** @var \Dorn\Loyalty\Model\ResourceModel\CoinsTransaction\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter(CoinsTransactionInterface::CUSTOMER_ID, $this->currentCustomer->getCustomerId());

        return $collection;
    }
}
