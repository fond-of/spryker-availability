<?php

namespace FondOfSpryker\Zed\Availability\Dependency\Facade;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductBridge as BaseAvailabilityToProductBridge;

class AvailabilityToProductBridge extends BaseAvailabilityToProductBridge implements AvailabilityToProductInterface
{
    /**
     * @param string $concreteSku
     *
     * @throws
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku): ProductConcreteTransfer
    {
        return $this->productFacade->getProductConcrete($concreteSku);
    }
}
