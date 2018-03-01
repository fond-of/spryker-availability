<?php

namespace FondOfSpryker\Zed\Availability;

use Codeception\Test\Unit;
use Spryker\Zed\Kernel\Container;

class AvailabilityDependencyProviderTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\Availability\AvailabilityDependencyProvider
     */
    protected $availabilityDependencyProvider;

    /**
     * @var \Spryker\Zed\Kernel\Container
     */
    protected $containerMock;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->availabilityDependencyProvider = new AvailabilityDependencyProvider();
        $this->containerMock = $this->make(Container::class);
    }

    /**
     * @return void
     */
    public function testProvideBusinessLayerDependencies()
    {
        $this->availabilityDependencyProvider->provideBusinessLayerDependencies($this->containerMock);

        $valueNames = $this->containerMock->keys();

        $this->assertTrue(in_array(AvailabilityDependencyProvider::FACADE_OMS, $valueNames));
        $this->assertTrue(in_array(AvailabilityDependencyProvider::FACADE_PRODDUCT, $valueNames));
        $this->assertTrue(in_array(AvailabilityDependencyProvider::FACADE_TOUCH, $valueNames));
        $this->assertTrue(in_array(AvailabilityDependencyProvider::FACADE_STOCK, $valueNames));
    }
}
