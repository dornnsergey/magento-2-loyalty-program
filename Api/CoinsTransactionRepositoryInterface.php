<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Api;

use Dorn\Loyalty\Api\Data\CoinsTransactionInterface;
use Dorn\Loyalty\Api\Data\CoinsTransactionSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface CoinsTransactionRepositoryInterface
{
    /**
     * @param  \Dorn\Loyalty\Api\Data\CoinsTransactionInterface  $coinsTransaction
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(CoinsTransactionInterface $coinsTransaction): bool;

    /**
     * @param  int  $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $id): bool;

    /**
     * @param  int  $id
     * @return \Dorn\Loyalty\Api\Data\CoinsTransactionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): CoinsTransactionInterface;

    /**
     * @param  \Dorn\Loyalty\Api\Data\CoinsTransactionInterface  $coinsTransaction
     * @return bool
     * @throws CouldNotSaveException
     */
    public function save(CoinsTransactionInterface $coinsTransaction): bool;

    /**
     * @param  \Magento\Framework\Api\SearchCriteriaInterface  $searchCriteria
     * @return \Dorn\Loyalty\Api\Data\CoinsTransactionSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): CoinsTransactionSearchResultsInterface;
}
