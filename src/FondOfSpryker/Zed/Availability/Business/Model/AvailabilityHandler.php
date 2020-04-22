<?php

namespace FondOfSpryker\Zed\Availability\Business\Model;

use FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Availability\Business\Model\AvailabilityHandler as BaseAvailabilityHandler;
use Spryker\Zed\Availability\Business\Model\SellableInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
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
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     * @param int $defaultMinimalQuantity
     */
    public function __construct(
        SellableInterface $sellable,
        AvailabilityToStockInterface $stockFacade,
        AvailabilityToTouchInterface $touchFacade,
        AvailabilityQueryContainerInterface $queryContainer,
        AvailabilityToProductInterface $productFacade,
        AvailabilityToStoreFacadeInterface $storeFacade,
        int $defaultMinimalQuantity
    ) {
        parent::__construct($sellable, $stockFacade, $touchFacade, $queryContainer, $productFacade, $storeFacade);

        $this->defaultMinimalQuantity = $defaultMinimalQuantity;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateAvailability($sku): void
    {
        $this->updateAvailabilityForStore($sku, $this->storeFacade->getCurrentStore());
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability
     */
    protected function saveAndTouchAvailability($sku, $quantity, StoreTransfer $storeTransfer)
    {
        $quantity = $this->prepareQuantityForAvailability($sku, $quantity);
        return parent::saveAndTouchAvailability($sku, $quantity, $storeTransfer);
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return int
     */
    protected function prepareQuantityForAvailability(string $sku, int $quantity): int
    {

        $minimalQuantity = $this->getMinimalQuantityForAvailability($sku);
        $quantity -= $minimalQuantity;

        if ($quantity < 0) {
            return 0;
        }

        return $quantity;
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    protected function getMinimalQuantityForAvailability(string $sku): int
    {
        $concreteProduct = $this->productFacade->getProductConcrete($sku);

        if (!$concreteProduct->getUseConfigForMinimalQuantity()) {
            return $concreteProduct->getMinimalQuantity();
        }

        return $this->defaultMinimalQuantity;
    }
}
