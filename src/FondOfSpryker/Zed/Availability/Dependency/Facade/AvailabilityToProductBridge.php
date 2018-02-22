<?php

namespace FondOfSpryker\Zed\Availability\Dependency\Facade;

use \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductBridge as BaseAvailabilityToProductBridge;

class AvailabilityToProductBridge extends BaseAvailabilityToProductBridge implements AvailabilityToProductInterface
{
    /**
     * @param string $concreteSku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku)
    {
        return $this->productFacade->getProductConcrete($concreteSku);
    }
}
