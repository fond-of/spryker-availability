<?php

namespace FondOfSpryker\Zed\Availability\Business\Model;

use FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Availability\Business\Model\AvailabilityHandler as SprykerAvailabilityHandler;
use Spryker\Zed\Availability\Business\Model\ProductAvailabilityCalculatorInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchFacadeInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface;

class AvailabilityHandler extends SprykerAvailabilityHandler
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
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    private $storeFacade;

    /**
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface $availabilityRepository
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface $availabilityEntityManager
     * @param \Spryker\Zed\Availability\Business\Model\ProductAvailabilityCalculatorInterface $availabilityCalculator
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchFacadeInterface $touchFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface $stockFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface $eventFacade
     * @param \FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface $productFacade
     * @param int $defaultMinimalQuantity
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        AvailabilityRepositoryInterface $availabilityRepository,
        AvailabilityEntityManagerInterface $availabilityEntityManager,
        ProductAvailabilityCalculatorInterface $availabilityCalculator,
        AvailabilityToTouchFacadeInterface $touchFacade,
        AvailabilityToStockFacadeInterface $stockFacade,
        AvailabilityToEventFacadeInterface $eventFacade,
        AvailabilityToProductInterface $productFacade,
        int $defaultMinimalQuantity,
        AvailabilityToStoreFacadeInterface $storeFacade
    ) {
        parent::__construct($availabilityRepository, $availabilityEntityManager, $availabilityCalculator, $touchFacade, $stockFacade, $eventFacade);

        $this->defaultMinimalQuantity = $defaultMinimalQuantity;
        $this->productFacade = $productFacade;
        $this->storeFacade = $storeFacade;
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
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function saveAndTouchAvailability(string $sku, Decimal $quantity, StoreTransfer $storeTransfer): int
    {
        $quantity = $this->prepareQuantityForAvailability($sku, $quantity->toInt());

        return parent::saveAndTouchAvailability($sku, new Decimal($quantity), $storeTransfer);
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
            $quantity = 0;
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
