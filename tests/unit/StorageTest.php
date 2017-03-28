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
        $data = new Data();
        $data->setData($data->mok);
        $this->assertTrue($storage->save($data));
        $data = new Data();
        $this->assertTrue($storage->load($data));
        $this->assertEquals($data->mok, $data->getData());

        $this->assertTrue($storage->has($data));
        $storage->remove($data);
        $this->assertFalse($storage->has($data));

        //$storage->saveData($data->mok);
        //$a = $storage
    }
}
