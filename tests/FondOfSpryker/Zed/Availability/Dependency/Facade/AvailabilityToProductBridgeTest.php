<?php

namespace FondOfSpryker\Zed\Availability\Dependency\Facade;

use Codeception\Test\Unit;
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
    protected $productConcreteTransfer;

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
        $productConcreteTransfer = $this->getMockBuilder('\Generated\Shared\Transfer\ProductConcreteTransfer');

        $this->productFacadeMock = $this->make(ProductFacade::class, [
            'getProductConcrete' => function ($concreteSku) {
                if ($concreteSku == 'TST-123-456-789') {
                    return $this->productConcreteTransfer;
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
    public function testGetProductConcrete()
    {
        $concreteSku = 'TST-123-456-789';

        try {
            $this->availabilityToProductBridge->getProductConcrete($concreteSku);
            $this->assertTrue(true);
        } catch (MissingProductException $e) {
            $this->fail();
        }
    }

    /**
     * @return void
     */
    public function testGetProductConcreteWithInvalidConcreteSku()
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
