<?php

namespace FondOfSpryker\Zed\Availability\Business\Model;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface;
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
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $spyAvailabilityQueryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $spyAvailabilityMock;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->concreteProductTransferMock = $this->getMockBuilder('\Generated\Shared\Transfer\ProductConcreteTransfer')
            ->disableOriginalConstructor()
            ->setMethods(['getUseConfigForMinimalQuantity', 'getMinimalQuantity'])
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

        $this->storeTransferMock = $this->getMockBuilder('\Generated\Shared\Transfer\StoreTransfer')
            ->disableOriginalConstructor()
            ->setMethods(['getIdStore'])
            ->getMock();

        $this->storeTransferMock
            ->method('getIdStore')
            ->willReturn(1);

        $this->storeFacadeMock
            ->method('getCurrentStore')
            ->willReturn($this->storeTransferMock);

        $this->spyAvailabilityQueryMock = $this->getMockBuilder("\Orm\Zed\Availability\Persistence\Base\SpyAvailabilityQuery")
            ->disableOriginalConstructor()
            ->setMethods(['findOne', 'findOneOrCreate'])
            ->getMock();

        $this->spyAvailabilityMock = $this->getMockBuilder("\Orm\Zed\Availability\Persistence\SpyAvailability")
            ->disableOriginalConstructor()
            ->setMethods(['getQuantity', 'isNew', 'setQuantity', 'setIsNeverOutOfStock', 'isColumnModified', 'save', 'getFkAvailabilityAbstract', 'setFkStore'])
            ->getMock();

        $this->spyAvailabilityAbstractQueryMock = $this->getMockBuilder('\Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery')
            ->disableOriginalConstructor()
            ->getMock();

        $this->availabilityHandler = new AvailabilityHandler(
            $this->sellableMock,
            $this->stockFacadeMock,
            $this->touchFacadeMock,
            $this->queryContainerMock,
            $this->productFacadeMock,
            $this->storeFacadeMock,
            10
        );
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testSaveCurrentAvailabilityWithDefaultMinimalQuantity()
    {
        $sku = 'TST-123-456-789';
        $abstractId = 1;
        $storeId = 1;

        $this->productFacadeMock->expects($this->atLeastOnce())
            ->method('getProductConcrete')
            ->with($sku)
            ->willReturn($this->concreteProductTransferMock);

        $this->concreteProductTransferMock->expects($this->atLeastOnce())
            ->method('getUseConfigForMinimalQuantity')
            ->willReturn(true);

        $this->concreteProductTransferMock->expects($this->never())
            ->method('getMinimalQuantity');

        $this->queryContainerMock->expects($this->atLeastOnce())
            ->method('queryAvailabilityBySkuAndIdStore')
            ->with($sku, $storeId)
            ->willReturn($this->spyAvailabilityQueryMock);

        $this->spyAvailabilityQueryMock->expects($this->atLeastOnce())
            ->method('findOneOrCreate')
            ->willReturn($this->spyAvailabilityMock);

        $this->spyAvailabilityMock->expects($this->atLeastOnce())
            ->method('getQuantity')
            ->willReturn(3);

        $this->spyAvailabilityMock->expects($this->atLeastOnce())
            ->method('isNew')
            ->willReturn(false);

        $this->stockFacadeMock->expects($this->atLeastOnce())
            ->method('isNeverOutOfStockForStore')
            ->with($sku, $this->storeTransferMock)
            ->willReturn(false);

        $this->spyAvailabilityMock->expects($this->atLeastOnce())
            ->method('isColumnModified')
            ->willReturn(false);

        $this->spyAvailabilityMock->expects($this->atLeastOnce())
            ->method('save');

        $this->spyAvailabilityMock->expects($this->atLeastOnce())
            ->method('getFkAvailabilityAbstract')
            ->willReturn($abstractId);

        $this->spyAvailabilityQueryMock->expects($this->atLeastOnce())
            ->method('findOne')
            ->willReturnOnConsecutiveCalls(
                $this->spyAvailabilityMock,
                $this->spyAvailabilityMock,
                12
            );

        $this->queryContainerMock->expects($this->atLeastOnce())
            ->method('queryAvailabilityAbstractByIdAvailabilityAbstract')
            ->with($abstractId, $storeId)
            ->willReturn($this->spyAvailabilityQueryMock);

        $this->queryContainerMock->expects($this->atLeastOnce())
            ->method('querySumQuantityOfAvailabilityAbstract')
            ->with($abstractId, $storeId)
            ->willReturn($this->spyAvailabilityQueryMock);

        $reflection = new \ReflectionClass(get_class($this->availabilityHandler));
        $method = $reflection->getMethod('saveAndTouchAvailability');
        $method->setAccessible(true);

        $method->invokeArgs($this->availabilityHandler, [$sku, 15, $this->storeTransferMock]);
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testSaveCurrentAvailability()
    {
        $sku = 'TST-123-456-789';
        $abstractId = 1;
        $storeId = 1;

        $this->productFacadeMock->expects($this->atLeastOnce())
            ->method('getProductConcrete')
            ->with($sku)
            ->willReturn($this->concreteProductTransferMock);

        $this->concreteProductTransferMock->expects($this->atLeastOnce())
            ->method('getUseConfigForMinimalQuantity')
            ->willReturn(false);

        $this->concreteProductTransferMock->expects($this->atLeastOnce())
            ->method('getMinimalQuantity')
            ->willReturn(10);

        $this->queryContainerMock->expects($this->atLeastOnce())
            ->method('queryAvailabilityBySkuAndIdStore')
            ->with($sku, $storeId)
            ->willReturn($this->spyAvailabilityQueryMock);

        $this->spyAvailabilityQueryMock->expects($this->atLeastOnce())
            ->method('findOneOrCreate')
            ->willReturn($this->spyAvailabilityMock);

        $this->spyAvailabilityMock->expects($this->atLeastOnce())
            ->method('getQuantity')
            ->willReturn(3);

        $this->spyAvailabilityMock->expects($this->atLeastOnce())
            ->method('isNew')
            ->willReturn(false);

        $this->stockFacadeMock->expects($this->atLeastOnce())
            ->method('isNeverOutOfStockForStore')
            ->with($sku, $this->storeTransferMock)
            ->willReturn(false);

        $this->spyAvailabilityMock->expects($this->atLeastOnce())
            ->method('isColumnModified')
            ->willReturn(false);

        $this->spyAvailabilityMock->expects($this->atLeastOnce())
            ->method('save');

        $this->spyAvailabilityMock->expects($this->atLeastOnce())
            ->method('getFkAvailabilityAbstract')
            ->willReturn($abstractId);

        $this->spyAvailabilityQueryMock->expects($this->atLeastOnce())
            ->method('findOne')
            ->willReturnOnConsecutiveCalls(
                $this->spyAvailabilityMock,
                $this->spyAvailabilityMock,
                12
            );

        $this->queryContainerMock->expects($this->atLeastOnce())
            ->method('queryAvailabilityAbstractByIdAvailabilityAbstract')
            ->with($abstractId, $storeId)
            ->willReturn($this->spyAvailabilityQueryMock);

        $this->queryContainerMock->expects($this->atLeastOnce())
            ->method('querySumQuantityOfAvailabilityAbstract')
            ->with($abstractId, $storeId)
            ->willReturn($this->spyAvailabilityQueryMock);

        $reflection = new \ReflectionClass(get_class($this->availabilityHandler));
        $method = $reflection->getMethod('saveAndTouchAvailability');
        $method->setAccessible(true);

        $method->invokeArgs($this->availabilityHandler, [$sku, 15, $this->storeTransferMock]);
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testSaveCurrentAvailabilityWithNewQuantityEqualsZero()
    {
        $sku = 'TST-123-456-789';
        $abstractId = 1;
        $storeId = 1;

        $this->productFacadeMock->expects($this->atLeastOnce())
            ->method('getProductConcrete')
            ->with($sku)
            ->willReturn($this->concreteProductTransferMock);

        $this->concreteProductTransferMock->expects($this->atLeastOnce())
            ->method('getUseConfigForMinimalQuantity')
            ->willReturn(false);

        $this->concreteProductTransferMock->expects($this->atLeastOnce())
            ->method('getMinimalQuantity')
            ->willReturn(10);

        $this->queryContainerMock->expects($this->atLeastOnce())
            ->method('queryAvailabilityBySkuAndIdStore')
            ->with($sku, $storeId)
            ->willReturn($this->spyAvailabilityQueryMock);

        $this->spyAvailabilityQueryMock->expects($this->atLeastOnce())
            ->method('findOneOrCreate')
            ->willReturn($this->spyAvailabilityMock);

        $this->spyAvailabilityMock->expects($this->atLeastOnce())
            ->method('getQuantity')
            ->willReturn(3);

        $this->spyAvailabilityMock->expects($this->atLeastOnce())
            ->method('isNew')
            ->willReturn(false);

        $this->stockFacadeMock->expects($this->atLeastOnce())
            ->method('isNeverOutOfStockForStore')
            ->with($sku, $this->storeTransferMock)
            ->willReturn(false);

        $this->spyAvailabilityMock->expects($this->atLeastOnce())
            ->method('isColumnModified')
            ->willReturn(false);

        $this->spyAvailabilityMock->expects($this->atLeastOnce())
            ->method('save');

        $this->spyAvailabilityMock->expects($this->atLeastOnce())
            ->method('getFkAvailabilityAbstract')
            ->willReturn($abstractId, $storeId);

        $this->spyAvailabilityQueryMock->expects($this->atLeastOnce())
            ->method('findOne')
            ->willReturnOnConsecutiveCalls(
                $this->spyAvailabilityMock,
                $this->spyAvailabilityMock,
                12
            );

        $this->queryContainerMock->expects($this->atLeastOnce())
            ->method('queryAvailabilityAbstractByIdAvailabilityAbstract')
            ->with($abstractId, $storeId)
            ->willReturn($this->spyAvailabilityQueryMock);

        $this->queryContainerMock->expects($this->atLeastOnce())
            ->method('querySumQuantityOfAvailabilityAbstract')
            ->with($abstractId, $storeId)
            ->willReturn($this->spyAvailabilityQueryMock);

        $reflection = new \ReflectionClass(get_class($this->availabilityHandler));
        $method = $reflection->getMethod('saveAndTouchAvailability');
        $method->setAccessible(true);

        $method->invokeArgs($this->availabilityHandler, [$sku, 0, $this->storeTransferMock]);
    }
}
