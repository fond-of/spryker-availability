<?php

namespace FondOfSpryker\Zed\Availability\Dependency\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Spryker\Zed\Product\Business\ProductFacade;

class AvailabilityToProductBridgeTest extends Unit
{
    /**
     * @var \Spryker\Zed\Product\Business\ProductFacade
     */
    protected $productFacadeMock;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransferMock;

    /**
     * @var \FondOfSpryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface
     */
    protected $availabilityToProductBridge;

    /**
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return void
     */
    protected function _before()
    {
        $productConcreteTransferMock = $this->productConcreteTransferMock = $this->getMockBuilder(ProductConcreteTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productFacadeMock = $this->make(ProductFacade::class, [
            'getProductConcrete' => static function ($concreteSku) use ($productConcreteTransferMock) {
                if ($concreteSku === 'TST-123-456-789') {
                    return $productConcreteTransferMock;
                }

                throw new MissingProductException(
                    sprintf(
                        'Tried to retrieve a product concrete with sku %s, but it does not exist.',
                        $concreteSku
                    )
                );
            },
        ]);

        $this->availabilityToProductBridge = new AvailabilityToProductBridge($this->productFacadeMock);
    }

    /**
     * @return void
     */
    public function testGetProductConcrete(): void
    {
        $concreteSku = 'TST-123-456-789';

        try {
            $productConcreteTransfer = $this->availabilityToProductBridge->getProductConcrete($concreteSku);
            $this->assertEquals($this->productConcreteTransferMock, $productConcreteTransfer);
        } catch (MissingProductException $e) {
            $this->fail();
        }
    }

    /**
     * @return void
     */
    public function testGetProductConcreteWithInvalidConcreteSku(): void
    {
        $concreteSku = 'TST-123-456-788';

        try {
            $this->availabilityToProductBridge->getProductConcrete($concreteSku);
            $this->fail();
        } catch (MissingProductException $e) {
            $this->assertTrue(true);
        }
    }
}
