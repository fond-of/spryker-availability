<?php

namespace FondOfSpryker\Zed\Availability\Business;

use FondOfSpryker\Zed\Availability\AvailabilityDependencyProvider;
use FondOfSpryker\Zed\Availability\Business\Model\AvailabilityHandler;
use Spryker\Zed\Availability\Business\AvailabilityBusinessFactory as SprykerAvailabilityBusinessFactory;
use Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductFacadeInterface;

/**
 * @method \FondOfSpryker\Zed\Availability\AvailabilityConfig getConfig()
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface getQueryContainer()
 */
class AvailabilityBusinessFactory extends SprykerAvailabilityBusinessFactory
{
    /**
     * @return \Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface
     */
    public function createAvailabilityHandler(): AvailabilityHandlerInterface
    {
        return new AvailabilityHandler(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createProductAvailabilityCalculator(),
            $this->getTouchFacade(),
            $this->getStockFacade(),
            $this->getEventFacade(),
            $this->getProductFacade(),
            $this->getConfig()->getDefaultMinimalQuantity(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductFacadeInterface
     */
    public function getProductFacade(): AvailabilityToProductFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_PRODUCT);
    }
}
