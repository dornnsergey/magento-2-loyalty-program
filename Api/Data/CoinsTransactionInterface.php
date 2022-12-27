<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Api\Data;

interface CoinsTransactionInterface
{
    public const ID = 'id';

    public const CUSTOMER_ID = 'customer_id';

    public const ORDER_ID = 'order_id';

    public const AMOUNT_OF_PURCHASE = 'amount_of_purchase';

    public const ADDED_BY_ADMIN = 'added_by_admin';

    public const COINS_RECEIVED = 'coins_received';

    public const COINS_SPEND = 'coins_spend';

    public const DATE_OF_PURCHASE = 'date_of_purchase';

    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = 'updated_at';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @param  int  $id
     * @return $this
     */
    public function setCustomerId(int $id): static;

    /**
     * @return int
     */
    public function getOrderId();

    /**
     * @param int $id
     * @return $this
     */
    public function setOrderId(int $id): static;

    /**
     * @return float
     */
    public function getAmountOfPurchase();

    /**
     * @param float $amountOfPurchase
     * @return $this
     */
    public function setAmountOfPurchase(float $amountOfPurchase): static;

    /**
     * @return bool
     */
    public function getAddedByAdmin();

    /**
     * @param bool $flag
     * @return $this
     */
    public function setAddedByAdmin(bool $flag): static;

    /**
     * @return float
     */
    public function getCoinsReceived();

    /**
     * @param float $coinsReceived
     * @return $this
     */
    public function setCoinsReceived(float $coinsReceived): static;

    /**
     * @return float
     */
    public function getCoinsSpend();

    /**
     * @param float $coinsSpend
     * @return $this
     */
    public function setCoinsSpend(float $coinsSpend): static;

    /**
     * @return string
     */
    public function getDateOfPurchase(): string;

    /**
     * @param  string  $dateOfPurchase
     * @return $this
     */
    public function setDateOfPurchase(string $dateOfPurchase): static;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @param  string  $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt): static;

    /**
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * @param  string  $updatedAt
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt): static;
}