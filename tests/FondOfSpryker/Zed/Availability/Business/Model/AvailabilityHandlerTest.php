<?php

namespace FondOfSpryker\Zed\Availability\Business\Model;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use ReflectionClass;
use Spryker\Zed\Availability\Business\Model\SellableInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface;

class AvailabilityHandlerTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\Availability\Business\Model\AvailabilityHandler
     */
    protected $availabilityHandler;

    /**
     * @var \Spryker\Zed\Availability\Business\Model\SellableInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $sellableMock;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $stockFacadeMock;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $touchFacadeMock;

    /**
     * @var \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $queryContainerMock;

    /**
     * @var \FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productFacadeMock;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $storeFacadeMock;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $storeTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $concreteProductTransferMock;

    /**
     * @var string
     */
    protected $sku;

    /**
     * @var int
     */
    protected $defaultMinimalQuantity;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->concreteProductTransferMock = $this->getMockBuilder(ProductConcreteTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sellableMock = $this->getMockBuilder(SellableInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->stockFacadeMock = $this->getMockBuilder(AvailabilityToStockInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->touchFacadeMock = $this->getMockBuilder(AvailabilityToTouchInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->queryContainerMock = $this->getMockBuilder(AvailabilityQueryContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productFacadeMock = $this->getMockBuilder(AvailabilityToProductInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeFacadeMock = $this->getMockBuilder(AvailabilityToStoreFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sku = 'TST-123-456-789';

        $this->defaultMinimalQuantity = 10;

        $this->availabilityHandler = new AvailabilityHandler(
            $this->sellableMock,
            $this->stockFacadeMock,
            $this->touchFacadeMock,
            $this->queryContainerMock,
            $this->productFacadeMock,
            $this->storeFacadeMock,
            $this->defaultMinimalQuantity
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
