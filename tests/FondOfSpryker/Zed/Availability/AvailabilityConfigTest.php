<?php

namespace FondOfSpryker\Zed\Availability;

use Codeception\Test\Unit;
use FondOfSpryker\Shared\Availability\AvailabilityConstants;

class AvailabilityConfigTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\Availability\AvailabilityConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $availabilityConfig;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->availabilityConfig = $this->getMockBuilder(AvailabilityConfig::class)
            ->setMethods(['get'])
            ->getMock();
    }

    /**
     * @return void
     */
    public function testGetDefaultMinQty(): void
    {
        $this->availabilityConfig->expects($this->atLeastOnce())
            ->method('get')
            ->with(AvailabilityConstants::DEFAULT_MINIMAL_QUANTITY, AvailabilityConstants::DEFAULT_MINIMAL_QUANTITY_VALUE)
            ->willReturn(AvailabilityConstants::DEFAULT_MINIMAL_QUANTITY_VALUE);

        $this->assertEquals(
            AvailabilityConstants::DEFAULT_MINIMAL_QUANTITY_VALUE,
            $this->availabilityConfig->getDefaultMinimalQuantity()
        );
    }

    /**
     * @return void
     */
    public function testGetCustomDefaultMinQty(): void
    {
        $this->availabilityConfig->expects($this->atLeastOnce())
            ->method('get')
            ->with(AvailabilityConstants::DEFAULT_MINIMAL_QUANTITY, AvailabilityConstants::DEFAULT_MINIMAL_QUANTITY_VALUE)
            ->willReturn(20);

        $this->assertEquals(
            20,
            $this->availabilityConfig->getDefaultMinimalQuantity()
        );
    }
}
