<?php

namespace FondOfSpryker\Zed\Availability\Business;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\Availability\AvailabilityConfig;
use FondOfSpryker\Zed\Availability\AvailabilityDependencyProvider;
use FondOfSpryker\Zed\Availability\Business\Model\AvailabilityHandler;
use FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;
use Spryker\Zed\Kernel\Container;

class AvailabilityBusinessFactoryTest extends Unit
{
    /**
     * @var null|\Spryker\Zed\Kernel\Container|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $containerMock;

    /**
     * @var null|\FondOfSpryker\Zed\Availability\AvailabilityConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $configMock;

    /**
     * @var null|\FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productFacadeMock;

    /**
     * @var null|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $omsFacadeMock;

    /**
     * @var null|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $stockFacadeMock;

    /**
     * @var null|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $touchFacadeMock;

    /**
     * @var null|\Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $queryContainerMock;

    /**
     * @var null|\FondOfSpryker\Zed\Availability\Business\AvailabilityBusinessFactory
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
                [AvailabilityDependencyProvider::FACADE_STOCK],
                [AvailabilityDependencyProvider::FACADE_TOUCH],
                [AvailabilityDependencyProvider::FACADE_PRODDUCT]
            )->willReturnOnConsecutiveCalls(
                $this->omsFacadeMock,
                $this->stockFacadeMock,
                $this->stockFacadeMock,
                $this->touchFacadeMock,
                $this->productFacadeMock
            );

        $this->configMock->expects($this->atLeastOnce())
            ->method('getDefaultMinimalQuantity')
            ->willReturn(10);

        $availabilityHandler = $this->availabilityBusinessFactory->createAvailabilityHandler();

        $this->assertInstanceOf(AvailabilityHandler::class, $availabilityHandler);
    }
}
