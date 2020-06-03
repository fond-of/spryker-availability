<?php

namespace FondOfSpryker\Zed\Availability\Business;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\Availability\AvailabilityConfig;
use FondOfSpryker\Zed\Availability\AvailabilityDependencyProvider;
use FondOfSpryker\Zed\Availability\Business\Model\AvailabilityHandler;
use FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchFacadeInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityEntityManager;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;
use Spryker\Zed\Availability\Persistence\AvailabilityRepository;
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
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $omsFacadeMock;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $stockFacadeMock;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchFacadeInterface|\PHPUnit\Framework\MockObject\MockObject|null
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
     * @var \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $eventFacade;

    /**
     * @return void
     */
    protected function _before(): void
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

        $this->omsFacadeMock = $this->getMockBuilder(AvailabilityToOmsFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->stockFacadeMock = $this->getMockBuilder(AvailabilityToStockFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->touchFacadeMock = $this->getMockBuilder(AvailabilityToTouchFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeFacadeMock = $this->getMockBuilder(AvailabilityToStoreFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->queryContainerMock = $this->getMockBuilder(AvailabilityQueryContainer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->repository = $this->getMockBuilder(AvailabilityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityManager = $this->getMockBuilder(AvailabilityEntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventFacade = $this->getMockBuilder(AvailabilityToEventFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->availabilityBusinessFactory = new AvailabilityBusinessFactory();

        $this->availabilityBusinessFactory->setConfig($this->configMock);
        $this->availabilityBusinessFactory->setContainer($this->containerMock);
        $this->availabilityBusinessFactory->setQueryContainer($this->queryContainerMock);
        $this->availabilityBusinessFactory->setRepository($this->repository);
        $this->availabilityBusinessFactory->setEntityManager($this->entityManager);
    }

    /**
     * @return void
     */
    public function testCreateAvailabilityHandler(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->withConsecutive(
                [AvailabilityDependencyProvider::FACADE_OMS],
                [AvailabilityDependencyProvider::FACADE_STOCK],
                [AvailabilityDependencyProvider::FACADE_TOUCH],
                [AvailabilityDependencyProvider::FACADE_STOCK],
                [AvailabilityDependencyProvider::FACADE_EVENT],
                [AvailabilityDependencyProvider::FACADE_PRODUCT],
                [AvailabilityDependencyProvider::FACADE_STORE]
            )->willReturnOnConsecutiveCalls(
                $this->omsFacadeMock,
                $this->stockFacadeMock,
                $this->touchFacadeMock,
                $this->stockFacadeMock,
                $this->eventFacade,
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
