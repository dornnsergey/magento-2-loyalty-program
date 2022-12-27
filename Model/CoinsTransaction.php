<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Model;

use Dorn\Loyalty\Api\Data\CoinsTransactionInterface;
use Dorn\Loyalty\Model\ResourceModel\CoinsTransaction as ResourceModel;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

class CoinsTransaction extends AbstractModel implements CoinsTransactionInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @param  int  $id
     * @return $this
     */
    public function setCustomerId(int $id): static
    {
        return $this->setData(self::CUSTOMER_ID, $id);
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * @param  int  $id
     * @return $this
     */
    public function setOrderId(int $id): static
    {
        return $this->setData(self::ORDER_ID, $id);
    }

    /**
     * @return bool
     */
    public function getAddedByAdmin()
    {
        return $this->getData(self::ADDED_BY_ADMIN);
    }

    /**
     * @param  bool  $flag
     * @return $this
     */
    public function setAddedByAdmin(bool $flag): static
    {
        return $this->setData(self::ADDED_BY_ADMIN, $flag);
    }

    /**
     * @return float
     */
    public function getCoinsReceived()
    {
        return $this->getData(self::COINS_RECEIVED);
    }

    /**
     * @param  float  $coinsReceived
     * @return $this
     */
    public function setCoinsReceived(float $coinsReceived): static
    {
        return $this->setData(self::COINS_RECEIVED, $coinsReceived);
    }

    /**
     * @return float
     */
    public function getCoinsSpend()
    {
        return $this->getData(self::COINS_SPEND);
    }

    /**
     * @param  float  $coinsSpend
     * @return $this
     */
    public function setCoinsSpend(float $coinsSpend): static
    {
        return $this->setData(self::COINS_SPEND, $coinsSpend);
    }

    /**
     * @return string
     */
    public function getDateOfPurchase(): string
    {
        return $this->getData(self::DATE_OF_PURCHASE);
    }

    /**
     * @param  string  $dateOfPurchase
     * @return $this
     */
    public function setDateOfPurchase(string $dateOfPurchase): static
    {
        return $this->setData(self::DATE_OF_PURCHASE, $dateOfPurchase);
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @param  string  $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt): static
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @param  string  $updatedAt
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt): static
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * @return float
     */
    public function getAmountOfPurchase()
    {
        return $this->getData(self::AMOUNT_OF_PURCHASE);
    }

    /**
     * @param  float  $amountOfPurchase
     * @return $this
     */
    public function setAmountOfPurchase(float $amountOfPurchase): static
    {
        return $this->setData(self::AMOUNT_OF_PURCHASE, $amountOfPurchase);
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function validateBeforeSave()
    {
        if ($this->getCoinsReceived() < 0) {
            throw new LocalizedException(__('The Coins Received Value can not be less than 0.'));
        }
    }
}
