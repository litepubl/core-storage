<?php

namespace litepubl\tests\storage;

use litepubl\core\storage\Storage;
use litepubl\core\storage\StorageInterface;
use litepubl\core\storage\Storable;
use litepubl\core\storage\serializer\SerializerInterface;
use litepubl\core\storage\serializer\JSon;
use litepubl\core\storage\serializer\Php;
use litepubl\core\storage\serializer\Serialize;
use litepubl\core\logmanager\LogManagerInterface;
use litepubl\core\logmanager\LazyProxy;

class StorageTest extends \Codeception\Test\Unit
{
    private $data = [
    's' => 'v',
    'i' => 4,
    'b' => false,
    'f' => 3.14,
    'a' => [
    'q' => 'w',
    ],
    'items' => [
    ['id' => 1],
    ['id' => 2],
    ]
    ];

    public function testMe()
    {
        $serializer = new Php();
        $logManager = new LazyProxy(
            function () {
                $manager = $this->prophesize(LogManagerInterface::class);
                return $manager->reveal();
            }
        );

        $storage = new Storage($serializer, $logManager, \Codeception\Configuration::outputDir(), 0666);
        $this->assertInstanceOf(Storage::class, $storage);
        $this->testStorage($storage);
    }

    private function testStorage(StorageInterface $storage)
    {

    }
}
