<?php

namespace FondOfSpryker\Zed\Availability;

use FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductBridge;
use Spryker\Zed\Availability\AvailabilityDependencyProvider as BaseAvailabilityDependencyProvider;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsBridge;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockBridge;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchBridge;
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
        $container[self::FACADE_OMS] = function (Container $container) {
            return new AvailabilityToOmsBridge($container->getLocator()->oms()->facade());
        };

        $container[self::FACADE_STOCK] = function (Container $container) {
            return new AvailabilityToStockBridge($container->getLocator()->stock()->facade());
        };

        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new AvailabilityToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::FACADE_PRODDUCT] = function (Container $container) {
            return new AvailabilityToProductBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }
}
