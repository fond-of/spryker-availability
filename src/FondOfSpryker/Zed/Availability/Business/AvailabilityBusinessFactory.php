<?php

namespace FondOfSpryker\Zed\Availability\Business;

use FondOfSpryker\Zed\Availability\AvailabilityDependencyProvider;
use FondOfSpryker\Zed\Availability\Business\Model\AvailabilityHandler;
use FondOfSpryker\Zed\Availability\Business\Model\Sellable;

use Spryker\Zed\Availability\Business\AvailabilityBusinessFactory as BaseAvailabilityBusinessFactory;

/**
 * @method \FondOfSpryker\Zed\Availability\AvailabilityConfig getConfig()
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface getQueryContainer()
 */
class AvailabilityBusinessFactory extends BaseAvailabilityBusinessFactory
{
    /**
     * @return \Spryker\Zed\Availability\Business\Model\SellableInterface
     */
    public function createSellableModel()
    {
        return new Sellable(
            $this->getOmsFacade(),
            $this->getStockFacade(),
            $this->getConfig()->getDefaultMinQty()
        );
    }

    /**
     * @return \Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createAvailabilityHandler()
    {
        return new AvailabilityHandler(
            $this->createSellableModel(),
            $this->getStockFacade(),
            $this->getTouchFacade(),
            $this->getQueryContainer(),
            $this->getProductFacade(),
            $this->getConfig()->getDefaultMinQty()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_PRODDUCT);
    }
}
