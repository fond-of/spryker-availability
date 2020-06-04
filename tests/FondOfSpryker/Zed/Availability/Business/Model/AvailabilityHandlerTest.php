<?php

namespace FondOfSpryker\Zed\Availability\Business\Model;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use ReflectionClass;
use Spryker\Zed\Availability\Business\Model\ProductAvailabilityCalculatorInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchFacadeInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityRepository;

class AvailabilityHandlerTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\Availability\Business\Model\AvailabilityHandler
     */
    protected $availabilityHandler;

    /**
     * @var \Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $entityManagerMock;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $stockFacadeMock;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $touchFacadeMock;

    /**
     * @var \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $repositoryMock;

    /**
     * @var \FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productFacadeMock;

    /**
     * @var \Spryker\Zed\Availability\Business\Model\ProductAvailabilityCalculatorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $availabilityCalculatorMock;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $storeTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $concreteProductTransferMock;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToEventFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $eventFacadeMock;

    /**
     * @var string
     */
    protected $sku;

    /**
     * @var int
     */
    protected $defaultMinimalQuantity;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $storeFacadeMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->repositoryMock = $this->getMockBuilder(AvailabilityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityManagerMock = $this->getMockBuilder(AvailabilityEntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->availabilityCalculatorMock = $this->getMockBuilder(ProductAvailabilityCalculatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->concreteProductTransferMock = $this->getMockBuilder(ProductConcreteTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->stockFacadeMock = $this->getMockBuilder(AvailabilityToStockFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->touchFacadeMock = $this->getMockBuilder(AvailabilityToTouchFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productFacadeMock = $this->getMockBuilder(AvailabilityToProductInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventFacadeMock = $this->getMockBuilder(AvailabilityToEventFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeFacadeMock = $this->getMockBuilder(AvailabilityToStoreFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sku = 'TST-123-456-789';

        $this->defaultMinimalQuantity = 10;

        $this->availabilityHandler = new AvailabilityHandler(
            $this->repositoryMock,
            $this->entityManagerMock,
            $this->availabilityCalculatorMock,
            $this->touchFacadeMock,
            $this->stockFacadeMock,
            $this->eventFacadeMock,
            $this->productFacadeMock,
            $this->defaultMinimalQuantity,
            $this->storeFacadeMock
        );
    }

    /**
     * @return void
     */
    public function testPrepareQuantityForAvailability(): void
    {
        $quantity = 15;

        $this->productFacadeMock->expects($this->atLeastOnce())
            ->method('getProductConcrete')
            ->with($this->sku)
            ->willReturn($this->concreteProductTransferMock);

        $this->concreteProductTransferMock->expects($this->atLeastOnce())
            ->method('getUseConfigForMinimalQuantity')
            ->willReturn(true);

        $this->concreteProductTransferMock->expects($this->never())
            ->method('getMinimalQuantity');

        $reflection = new ReflectionClass(get_class($this->availabilityHandler));

        $method = $reflection->getMethod('prepareQuantityForAvailability');
        $method->setAccessible(true);

        $preparedQuantity = $method->invokeArgs($this->availabilityHandler, [
            $this->sku,
            $quantity,
        ]);

        $this->assertEquals($quantity - $this->defaultMinimalQuantity, $preparedQuantity);
    }

    /**
     * @return void
     */
    public function testPrepareQuantityForAvailabilityWithCustomMinimalQuantity(): void
    {
        $quantity = 15;
        $minimalQuantity = 11;

        $this->productFacadeMock->expects($this->atLeastOnce())
            ->method('getProductConcrete')
            ->with($this->sku)
            ->willReturn($this->concreteProductTransferMock);

        $this->concreteProductTransferMock->expects($this->atLeastOnce())
            ->method('getUseConfigForMinimalQuantity')
            ->willReturn(false);

        $this->concreteProductTransferMock->expects($this->atLeastOnce())
            ->method('getMinimalQuantity')
            ->willReturn($minimalQuantity);

        $reflection = new ReflectionClass(get_class($this->availabilityHandler));

        $method = $reflection->getMethod('prepareQuantityForAvailability');
        $method->setAccessible(true);

        $preparedQuantity = $method->invokeArgs($this->availabilityHandler, [
            $this->sku,
            $quantity,
        ]);

        $this->assertEquals($quantity - $minimalQuantity, $preparedQuantity);
    }

    /**
     * @return void
     */
    public function testPrepareQuantityForAvailabilityWithQuantityZero(): void
    {
        $quantity = 0;

        $this->productFacadeMock->expects($this->atLeastOnce())
            ->method('getProductConcrete')
            ->with($this->sku)
            ->willReturn($this->concreteProductTransferMock);

        $this->concreteProductTransferMock->expects($this->atLeastOnce())
            ->method('getUseConfigForMinimalQuantity')
            ->willReturn(true);

        $this->concreteProductTransferMock->expects($this->never())
            ->method('getMinimalQuantity');

        $reflection = new ReflectionClass(get_class($this->availabilityHandler));

        $method = $reflection->getMethod('prepareQuantityForAvailability');
        $method->setAccessible(true);

        $preparedQuantity = $method->invokeArgs($this->availabilityHandler, [
            $this->sku,
            $quantity,
        ]);

        $this->assertEquals(0, $preparedQuantity);
    }
}
