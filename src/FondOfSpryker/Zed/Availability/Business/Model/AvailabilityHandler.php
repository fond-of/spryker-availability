<?php

namespace FondOfSpryker\Zed\Availability\Business\Model;

use FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface;
use Spryker\Zed\Availability\Business\Model\AvailabilityHandler as BaseAvailabilityHandler;
use Spryker\Zed\Availability\Business\Model\SellableInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface;

class AvailabilityHandler extends BaseAvailabilityHandler
{
    /**
     * @var \FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface
     */
    protected $productFacade;

    /**
     * @var int
     */
    protected $defaultMinimalQuantity;

    /**
     * @param \Spryker\Zed\Availability\Business\Model\SellableInterface $sellable
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface $stockFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface $touchFacade
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface $queryContainer
     * @param \FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface $productFacade
     * @param int $defaultMinimalQuantity
     */
    public function __construct(
        SellableInterface $sellable,
        AvailabilityToStockInterface $stockFacade,
        AvailabilityToTouchInterface $touchFacade,
        AvailabilityQueryContainerInterface $queryContainer,
        AvailabilityToProductInterface $productFacade,
        int $defaultMinimalQuantity
    ) {
        parent::__construct($sellable, $stockFacade, $touchFacade, $queryContainer, $productFacade);

        $this->defaultMinimalQuantity = $defaultMinimalQuantity;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability
     */
    protected function saveAndTouchAvailability($sku, $quantity)
    {
        $minimalQuantity = $this->getMinimalQuantityForAvailability($sku);

        $quantity = $quantity - $minimalQuantity;

        if ($quantity < 0) {
            $quantity = 0;
        }

        return parent::saveAndTouchAvailability($sku, $quantity);
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    protected function getMinimalQuantityForAvailability($sku)
    {
        $concreteProduct = $this->productFacade->getProductConcrete($sku);

        if (!$concreteProduct->getUseConfigForMinimalQuantity()) {
            return $concreteProduct->getMinimalQuantity();
        }

        return $this->defaultMinimalQuantity;
    }
}
