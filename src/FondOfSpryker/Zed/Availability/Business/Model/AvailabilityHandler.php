<?php

namespace FondOfSpryker\Zed\Availability\Business\Model;

use FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Availability\Business\Model\AvailabilityHandler as BaseAvailabilityHandler;
use Spryker\Zed\Availability\Business\Model\ProductAvailabilityCalculatorInterface;
use Spryker\Zed\Availability\Business\Model\SellableInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface;

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
     * AvailabilityHandler constructor.
     * @param  \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface  $availabilityRepository
     * @param  \Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface  $availabilityEntityManager
     * @param  \Spryker\Zed\Availability\Business\Model\ProductAvailabilityCalculatorInterface  $availabilityCalculator
     * @param  \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchFacadeInterface  $touchFacade
     * @param  \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface  $stockFacade
     * @param  \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface  $eventFacade
     * @param  \FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface  $productFacade
     * @param  int  $defaultMinimalQuantity
     */
    public function __construct(
        AvailabilityRepositoryInterface $availabilityRepository,
        AvailabilityEntityManagerInterface $availabilityEntityManager,
        ProductAvailabilityCalculatorInterface $availabilityCalculator,
        AvailabilityToTouchFacadeInterface $touchFacade,
        AvailabilityToStockFacadeInterface $stockFacade,
        AvailabilityToEventFacadeInterface $eventFacade,
        AvailabilityToProductInterface $productFacade,
        int $defaultMinimalQuantity
    ) {
        parent::__construct($availabilityRepository, $availabilityEntityManager, $availabilityCalculator, $touchFacade, $stockFacade, $eventFacade);

        $this->defaultMinimalQuantity = $defaultMinimalQuantity;
        $this->productFacade = $productFacade;
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
        $minimalQuantity = $this->getMinimalQuantityForAvailability($sku);

        $quantity = $quantity->toInt() - $minimalQuantity;

        if ($quantity < 0) {
            $quantity = 0;
        }

        return parent::saveAndTouchAvailability($sku, $quantity, $storeTransfer);
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
