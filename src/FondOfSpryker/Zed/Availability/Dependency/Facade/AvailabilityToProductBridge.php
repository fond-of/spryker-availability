<?php

namespace FondOfSpryker\Zed\Availability\Dependency\Facade;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductFacadeBridge as SprykerAvailabilityToProductFacadeBridge;

class AvailabilityToProductBridge extends SprykerAvailabilityToProductFacadeBridge implements AvailabilityToProductInterface
{
    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku): ProductConcreteTransfer
    {
        return $this->productFacade->getProductConcrete($concreteSku);
    }
}
