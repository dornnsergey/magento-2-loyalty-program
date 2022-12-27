<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Api\Data;

interface CoinsTransactionSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return CoinsTransactionInterface[]
     */
    public function getItems(): array;

    /**
     * @param  CoinsTransactionInterface[] $items
     */
    public function setItems(array $items): static;
}
