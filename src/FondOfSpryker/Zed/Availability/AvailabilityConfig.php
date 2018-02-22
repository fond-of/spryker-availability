<?php

namespace FondOfSpryker\Zed\Availability;

use FondOfSpryker\Shared\Availability\AvailabilityConstants;
use Spryker\Zed\Availability\AvailabilityConfig as BaseAvailabilityConfig;

class AvailabilityConfig extends BaseAvailabilityConfig
{
    /**
     * @return float
     */
    public function getDefaultMinQty(): float
    {
        return $this->get(AvailabilityConstants::DEFAULT_MIN_QTY, AvailabilityConstants::DEFAULT_MIN_QTY_VALUE);
    }
}
