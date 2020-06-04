<?php

namespace FondOfSpryker\Zed\Availability\Dependency\Facade;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductFacadeInterface as SprykerAvailabilityToProductFacadeInterface;

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
