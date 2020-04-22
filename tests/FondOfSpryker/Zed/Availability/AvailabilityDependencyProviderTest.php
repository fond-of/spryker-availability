<?php

namespace FondOfSpryker\Zed\Availability;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\BundleProxy;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\Stock\Business\StockFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
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
     * @var \Spryker\Zed\Kernel\Locator|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $locatorMock;

    /**
     * @var \Spryker\Shared\Kernel\BundleProxy|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $bundleProxyMock;

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
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacadeMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->availabilityDependencyProvider = new AvailabilityDependencyProvider();

        $this->containerMock = $this->getMockBuilder(Container::class)
            ->setMethodsExcept(['factory', 'set', 'offsetSet', 'get', 'offsetGet'])
            ->getMock();

        $this->locatorMock = $this->getMockBuilder(Locator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->bundleProxyMock = $this->getMockBuilder(BundleProxy::class)
            ->disableOriginalConstructor()
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

        $this->storeFacadeMock = $this->getMockBuilder(StoreFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return void
     */
    public function testProvideBusinessLayerDependencies(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('getLocator')
            ->willReturn($this->locatorMock);

        $this->locatorMock->expects($this->atLeastOnce())
            ->method('__call')
            ->withConsecutive(['oms'], ['stock'], ['touch'], ['product'], ['store'])
            ->willReturn($this->bundleProxyMock);

        $this->bundleProxyMock->expects($this->atLeastOnce())
            ->method('__call')
            ->with('facade')
            ->willReturnOnConsecutiveCalls(
                $this->omsFacadeMock,
                $this->stockFacadeMock,
                $this->touchFacadeMock,
                $this->productFacadeMock,
                $this->storeFacadeMock
            );

        $this->availabilityDependencyProvider->provideBusinessLayerDependencies($this->containerMock);

        $this->assertNotNull($this->containerMock[AvailabilityDependencyProvider::FACADE_OMS]);
        $this->assertNotNull($this->containerMock[AvailabilityDependencyProvider::FACADE_STOCK]);
        $this->assertNotNull($this->containerMock[AvailabilityDependencyProvider::FACADE_TOUCH]);
        $this->assertNotNull($this->containerMock[AvailabilityDependencyProvider::FACADE_PRODUCT]);
        $this->assertNotNull($this->containerMock[AvailabilityDependencyProvider::FACADE_STORE]);
    }
}
