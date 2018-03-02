<?php

namespace FondOfSpryker\Zed\Availability;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\AbstractLocator;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\Stock\Business\StockFacadeInterface;
use Spryker\Zed\Touch\Business\TouchFacadeInterface;

class AvailabilityDependencyProviderTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\Availability\AvailabilityDependencyProvider|null
     */
    protected $availabilityDependencyProvider;

    /**
     * @var \Spryker\Zed\Kernel\Container|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $containerMock;

    /**
     * @var \Spryker\Shared\Kernel\AbstractLocator|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $locatorMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $omsMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $productMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $touchMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $stockMock;

    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacadeInterface|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $omsFacadeMock;

    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $productFacadeMock;

    /**
     * @var \Spryker\Zed\Stock\Business\StockFacadeInterface|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $stockFacadeMock;

    /**
     * @var \Spryker\Zed\Touch\Business\TouchFacadeInterface|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $touchFacadeMock;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->availabilityDependencyProvider = new AvailabilityDependencyProvider();

        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->setMethods(['getLocator'])
            ->getMock();

        $this->locatorMock = $this->getMockBuilder(AbstractLocator::class)
            ->disableOriginalConstructor()
            ->setMethods(['oms', 'product', 'touch', 'stock', 'locate'])
            ->getMock();

        $this->omsMock = $this->getMockBuilder('\Generated\Zed\Ide\Oms')
            ->disableOriginalConstructor()
            ->setMethods(['facade'])
            ->getMock();

        $this->productMock = $this->getMockBuilder('\Generated\Zed\Ide\Product')
            ->disableOriginalConstructor()
            ->setMethods(['facade'])
            ->getMock();

        $this->touchMock = $this->getMockBuilder('\Generated\Zed\Ide\Touch')
            ->disableOriginalConstructor()
            ->setMethods(['facade'])
            ->getMock();

        $this->stockMock = $this->getMockBuilder('\Generated\Zed\Ide\Stock')
            ->disableOriginalConstructor()
            ->setMethods(['facade'])
            ->getMock();

        $this->omsFacadeMock = $this->getMockBuilder(OmsFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productFacadeMock = $this->getMockBuilder(ProductFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->stockFacadeMock = $this->getMockBuilder(StockFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->touchFacadeMock = $this->getMockBuilder(TouchFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return void
     */
    public function testProvideBusinessLayerDependencies()
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('getLocator')
            ->willReturn($this->locatorMock);

        $this->locatorMock->expects($this->atLeastOnce())
            ->method('oms')
            ->willReturn($this->omsMock);

        $this->locatorMock->expects($this->atLeastOnce())
            ->method('touch')
            ->willReturn($this->touchMock);

        $this->locatorMock->expects($this->atLeastOnce())
            ->method('product')
            ->willReturn($this->productMock);

        $this->locatorMock->expects($this->atLeastOnce())
            ->method('stock')
            ->willReturn($this->stockMock);

        $this->omsMock->expects($this->atLeastOnce())
            ->method('facade')
            ->willReturn($this->omsFacadeMock);

        $this->productMock->expects($this->atLeastOnce())
            ->method('facade')
            ->willReturn($this->productFacadeMock);

        $this->stockMock->expects($this->atLeastOnce())
            ->method('facade')
            ->willReturn($this->stockFacadeMock);

        $this->touchMock->expects($this->atLeastOnce())
            ->method('facade')
            ->willReturn($this->touchFacadeMock);

        $this->availabilityDependencyProvider->provideBusinessLayerDependencies($this->containerMock);

        $valueNames = $this->containerMock->keys();

        $this->assertTrue(in_array(AvailabilityDependencyProvider::FACADE_OMS, $valueNames));
        $this->assertTrue(in_array(AvailabilityDependencyProvider::FACADE_PRODDUCT, $valueNames));
        $this->assertTrue(in_array(AvailabilityDependencyProvider::FACADE_TOUCH, $valueNames));
        $this->assertTrue(in_array(AvailabilityDependencyProvider::FACADE_STOCK, $valueNames));

        $this->assertNotNull($this->containerMock[AvailabilityDependencyProvider::FACADE_OMS]);
        $this->assertNotNull($this->containerMock[AvailabilityDependencyProvider::FACADE_PRODDUCT]);
        $this->assertNotNull($this->containerMock[AvailabilityDependencyProvider::FACADE_TOUCH]);
        $this->assertNotNull($this->containerMock[AvailabilityDependencyProvider::FACADE_STOCK]);
    }
}
