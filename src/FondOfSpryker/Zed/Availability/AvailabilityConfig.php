<?php

namespace FondOfSpryker\Zed\Availability;

use FondOfSpryker\Shared\Availability\AvailabilityConstants;
use Spryker\Zed\Availability\AvailabilityConfig as BaseAvailabilityConfig;

class AvailabilityConfig extends BaseAvailabilityConfig
{
    /**
     * @return int
     */
    public function getDefaultMinQty(): int
    {
        return $this->get(AvailabilityConstants::DEFAULT_MINIMAL_QUANTITY, AvailabilityConstants::DEFAULT_MINIMAL_QUANTITY_VALUE);
    }
}
