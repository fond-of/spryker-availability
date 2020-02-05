<?php

namespace Generated\Shared\Transfer;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class StoreTransfer extends AbstractTransfer
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'UNIT';
    }

    /**
     * @module CmsBlock|Discount|ProductBundle|ProductMeasurementUnit|Store
     *
     * @return int
     */
    public function getIdStore()
    {
        return 0;
    }

    /**
     * @module ProductBundle|Store
     *
     * @return array
     */
    public function getStoresWithSharedPersistence()
    {
        return [];
    }

    /**
     * @module Store
     *
     * @return string
     */
    public function getSelectedCurrencyIsoCode()
    {
        return '';
    }

    /**
     * @module Store
     *
     * @return string
     */
    public function getDefaultCurrencyIsoCode()
    {
        return '';
    }

    /**
     * @module Store
     *
     * @return array
     */
    public function getAvailableCurrencyIsoCodes()
    {
        return [];
    }

    /**
     * @module Store
     *
     * @return array
     */
    public function getAvailableLocaleIsoCodes()
    {
        return [];
    }

    /**
     * @module Store
     *
     * @return array
     */
    public function getQueuePools()
    {
        return [];
    }
}
