<?php

namespace FondOfSpryker\Zed\Availability;

use Codeception\Test\Unit;
use org\bovigo\vfs\vfsStream;
use Spryker\Shared\Config\Config;

class AvailabilityConfigTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\Availability\AvailabilityConfig
     */
    protected $availabilityConfig;

    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $vfsStreamDirectory;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->vfsStreamDirectory = vfsStream::setup('root', null, [
            'config' => [
                'Shared' => [
                    'stores.php' => file_get_contents(codecept_data_dir('stores.php')),
                    'config_default.php' => file_get_contents(codecept_data_dir('empty_config_default.php')),
                ],
            ],
        ]);

        $this->availabilityConfig = new AvailabilityConfig();
    }

    /**
     * @return void
     */
    public function testGetDefaultMinQty()
    {
        Config::getInstance()->init();

        $this->assertEquals(10, $this->availabilityConfig->getDefaultMinQty());
    }

    /**
     * @return void
     */
    public function testGetCustomDefaultMinQty()
    {
        $fileUrl = vfsStream::url('root/config/Shared/config_default.php');
        $newFileContent = file_get_contents(codecept_data_dir('config_default.php'));
        file_put_contents($fileUrl, $newFileContent);

        Config::getInstance()->init();

        $this->assertEquals(50, $this->availabilityConfig->getDefaultMinQty());
    }
}
