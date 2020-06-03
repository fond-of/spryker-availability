<?php

namespace FondOfSpryker\Zed\Availability\Dependency\Facade;

use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductFacadeInterface as SprykerAvailabilityToProductFacadeInterface;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface AvailabilityToProductInterface extends SprykerAvailabilityToProductFacadeInterface
{
    /**
     * @param string $concreteSku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku): ProductConcreteTransfer;
}
