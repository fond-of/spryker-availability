<?php

namespace FondOfSpryker\Zed\Availability\Business\Model;

use Spryker\Zed\Availability\Business\Model\Sellable as BaseSellable;

use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface;

class Sellable extends BaseSellable
{
    protected $minQty;

    /**
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface $omsFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface $stockFacade
     * @param float $minQty
     */
    public function __construct(
        AvailabilityToOmsInterface $omsFacade,
        AvailabilityToStockInterface $stockFacade,
        float $minQty
    ) {
        parent::__construct($omsFacade, $stockFacade);
        $this->minQty =  $minQty;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity)
    {
        return parent::isProductSellable($sku, $quantity);
        /*if ($this->stockFacade->isNeverOutOfStock($sku)) {
            return true;
        }

        $realStock = $this->calculateStockForProduct($sku);

        return $realStock >= $quantity && $realStock >= $this->minQty;*/
    }
}
