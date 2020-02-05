<?php

namespace FondOfSpryker\Zed\Availability\Business;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\Availability\AvailabilityConfig;
use FondOfSpryker\Zed\Availability\AvailabilityDependencyProvider;
use FondOfSpryker\Zed\Availability\Business\Model\AvailabilityHandler;
use FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;
use Spryker\Zed\Kernel\Container;

class AvailabilityBusinessFactoryTest extends Unit
{
    /**
     * @var \Spryker\Zed\Kernel\Container|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $containerMock;

    /**
     * @var \FondOfSpryker\Zed\Availability\AvailabilityConfig|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $configMock;

    /**
     * @var \FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $productFacadeMock;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $omsFacadeMock;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $stockFacadeMock;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $touchFacadeMock;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $storeFacadeMock;

    /**
     * @var \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $queryContainerMock;

    /**
     * @var \FondOfSpryker\Zed\Availability\Business\AvailabilityBusinessFactory|null
     */
    protected $availabilityBusinessFactory;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock = $this->getMockBuilder(AvailabilityConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productFacadeMock = $this->getMockBuilder(AvailabilityToProductInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->omsFacadeMock = $this->getMockBuilder(AvailabilityToOmsInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->stockFacadeMock = $this->getMockBuilder(AvailabilityToStockInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->touchFacadeMock = $this->getMockBuilder(AvailabilityToTouchInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeFacadeMock = $this->getMockBuilder(AvailabilityToStoreFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->queryContainerMock = $this->getMockBuilder(AvailabilityQueryContainer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->availabilityBusinessFactory = new AvailabilityBusinessFactory();

        $this->availabilityBusinessFactory->setConfig($this->configMock)
            ->setContainer($this->containerMock)
            ->setQueryContainer($this->queryContainerMock);
    }

    /**
     * @return void
     */
    public function testCreateAvailabilityHandler()
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('offsetExists')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('offsetGet')
            ->withConsecutive(
                [AvailabilityDependencyProvider::FACADE_OMS],
                [AvailabilityDependencyProvider::FACADE_STOCK],
                [AvailabilityDependencyProvider::FACADE_STORE],
                [AvailabilityDependencyProvider::FACADE_STOCK],
                [AvailabilityDependencyProvider::FACADE_TOUCH],
                [AvailabilityDependencyProvider::FACADE_PRODUCT],
                [AvailabilityDependencyProvider::FACADE_STORE]
            )->willReturnOnConsecutiveCalls(
                $this->omsFacadeMock,
                $this->stockFacadeMock,
                $this->storeFacadeMock,
                $this->stockFacadeMock,
                $this->touchFacadeMock,
                $this->productFacadeMock,
                $this->storeFacadeMock
            );

        $this->configMock->expects($this->atLeastOnce())
            ->method('getDefaultMinimalQuantity')
            ->willReturn(10);

        $availabilityHandler = $this->availabilityBusinessFactory->createAvailabilityHandler();

        $this->assertInstanceOf(AvailabilityHandler::class, $availabilityHandler);
    }
}
