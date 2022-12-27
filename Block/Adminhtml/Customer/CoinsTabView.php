<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Block\Adminhtml\Customer;

use Dorn\Loyalty\Api\Data\CoinsTransactionInterface;
use Dorn\Loyalty\Model\ResourceModel\CoinsTransaction\CollectionFactory;
use Magento\Customer\Model\CustomerIdProvider;

class CoinsTabView extends \Magento\Backend\Block\Widget\Grid\Extended
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        private CollectionFactory $collectionFactory,
        private CustomerIdProvider $customerProvider,
        array $data = []
    ) {
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _prepareCollection(): static
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter(
            CoinsTransactionInterface::CUSTOMER_ID,
            $this->customerProvider->getCustomerId()
        );

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @throws \Exception
     */
    protected function _prepareColumns(): static
    {
        $this->addColumn(
            'occasion',
            [
                'index'  => 'occasion',
                'header' => __('Occasion')
            ]
        );

        $this->addColumn(
            'amount_of_purchase',
            [
                'index'    => 'amount_of_purchase',
                'header'   => __('Amount of Purchase'),
                'type'     => 'currency',
                'currency' => 'order_currency_code',
                'rate'     => 1
            ]
        );

        $this->addColumn(
            'coins_received',
            [
                'index'  => 'coins_received',
                'header' => __('Coins Received'),
                'type'   => 'number',
            ]
        );

        $this->addColumn(
            'coins_spend',
            [
                'index'  => 'coins_spend',
                'header' => __('Coins Spend'),
                'type'   => 'number',
            ]
        );

        $this->addColumn(
            'created_at',
            [
                'index'  => 'created_at',
                'header' => __('Date of Purchase'),
                'type'   => 'datetime'
            ]
        );

        $this->addColumn(
            'action',
            [
                'header'           => __('Edit'),
                'type'             => 'action',
                'getter'           => 'getId',
                'filter'           => false,
                'sortable'         => false,
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action',
                'actions'          => [
                    [
                        'caption' => __('Edit'),
                        'url'     => [
                            'base' => '*/*/edit'
                        ],
                        'field'   => 'id'
                    ]
                ],
            ]
        );

        return parent::_prepareColumns();
    }

    protected function _afterLoadCollection()
    {
        foreach ($this->getCollection() as $item) {
            /** @var \Dorn\Loyalty\Api\Data\CoinsTransactionInterface $item */
            $occasion = $item->getAddedByAdmin() ? __('Added by admin') : $item->getOrderId();

            $item->setOccasion($occasion);
        }
    }
}