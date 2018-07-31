<?php

namespace FondOfSpryker\Zed\Availability;

use FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductBridge;
use Spryker\Zed\Availability\AvailabilityDependencyProvider as BaseAvailabilityDependencyProvider;
use Spryker\Zed\Kernel\Container;

class AvailabilityDependencyProvider extends BaseAvailabilityDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new AvailabilityToProductBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }
}
