<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CoinsTransaction extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('coins_transaction', 'id');
    }
}
