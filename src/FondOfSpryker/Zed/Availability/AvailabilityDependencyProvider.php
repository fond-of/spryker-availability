<?php

namespace FondOfSpryker\Zed\Availability;

use FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductBridge;
use Spryker\Zed\Availability\AvailabilityDependencyProvider as SprykerAvailabilityDependencyProvider;
use Spryker\Zed\Kernel\Container;

class AvailabilityDependencyProvider extends SprykerAvailabilityDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new AvailabilityToProductBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }
}
