<?php

namespace FondOfSpryker\Zed\Availability\Dependency\Facade;

use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToProductInterface as BaseAvailabilityToProductInterface;

interface AvailabilityToProductInterface extends BaseAvailabilityToProductInterface
{
    /**
     * @param string $concreteSku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku);
}
