<?php

namespace FondOfSpryker\Zed\Availability\Business;

use FondOfSpryker\Zed\Availability\AvailabilityDependencyProvider;
use FondOfSpryker\Zed\Availability\Business\Model\AvailabilityHandler;
use Spryker\Zed\Availability\Business\AvailabilityBusinessFactory as BaseAvailabilityBusinessFactory;
use Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface;

/**
 * @method \FondOfSpryker\Zed\Availability\AvailabilityConfig getConfig()
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface getQueryContainer()
 */
class AvailabilityBusinessFactory extends BaseAvailabilityBusinessFactory
{
    /**
     * @return \Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface
     */
    public function createAvailabilityHandler(): AvailabilityHandlerInterface
    {
        return new AvailabilityHandler(
            $this->createSellableModel(),
            $this->getStockFacade(),
            $this->getTouchFacade(),
            $this->getQueryContainer(),
            $this->getProductFacade(),
            $this->getStoreFacade(),
            $this->getConfig()->getDefaultMinimalQuantity()
        );
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_PRODUCT);
    }
}
