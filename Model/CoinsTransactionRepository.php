<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Model;

use Dorn\Loyalty\Api\CoinsTransactionRepositoryInterface;
use Dorn\Loyalty\Api\Data\CoinsTransactionInterface;
use Dorn\Loyalty\Api\Data\CoinsTransactionSearchResultsInterface;
use Dorn\Loyalty\Model\ResourceModel\CoinsTransaction as ResourceModel;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class CoinsTransactionRepository implements CoinsTransactionRepositoryInterface
{
    public function __construct(
        private ResourceModel $resource,
        private CoinsTransactionFactory $coinsTransactionFactory,
        private ResourceModel\Collection $collectionFactory,
        private CollectionProcessorInterface $collectionProcessor,
        private CoinsTransactionSearchResultsFactory $searchResultsFactory
    ) {
    }

    /**
     * @throws CouldNotDeleteException
     */
    public function delete(CoinsTransactionInterface $coinsTransaction): bool
    {
        try {
            $this->resource->delete($coinsTransaction);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(
                __('Could not delete the coins transaction: %1', $e->getMessage())
            );
        }

        return true;
    }

    /**
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $id): bool
    {
        return $this->delete($this->getById($id));
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getById(int $id): CoinsTransactionInterface
    {
        $coinsTransaction = $this->coinsTransactionFactory->create();

        $this->resource->load($coinsTransaction, $id);
        if (! $coinsTransaction->getId()) {
            throw new NoSuchEntityException(__('The coins transaction with the "%1" ID doesn\'t exists.', $id));
        }

        return $coinsTransaction;
    }

    /**
     * @throws CouldNotSaveException
     */
    public function save(CoinsTransactionInterface $coinsTransaction): bool
    {
        try {
            $this->resource->save($coinsTransaction);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('Could not save the book: %1', $e->getMessage())
            );
        }

        return true;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): CoinsTransactionSearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
