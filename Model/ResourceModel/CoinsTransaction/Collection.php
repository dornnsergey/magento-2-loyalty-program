<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Model\ResourceModel\CoinsTransaction;

use Dorn\Loyalty\Model\CoinsTransaction as Model;
use Dorn\Loyalty\Model\ResourceModel\CoinsTransaction as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
