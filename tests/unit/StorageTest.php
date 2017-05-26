<?php

namespace litepubl\tests\storage;

use litepubl\core\storage\StorageInterface;
use litepubl\core\storage\Storage;
use litepubl\core\storage\Pool;
use litepubl\core\storage\PoolInterface;
use litepubl\core\storage\LockerInterface;
use litepubl\core\storage\FileLocker;
use litepubl\core\storage\MemCacheStorage;
use litepubl\core\storage\Composite;
use litepubl\core\storage\StorableInterface;
use litepubl\core\storage\Data;
use litepubl\core\storage\serializer\SerializerInterface;
use litepubl\core\storage\serializer\JSon;
use litepubl\core\storage\serializer\Php;
use litepubl\core\storage\serializer\Serialize;
use litepubl\core\logmanager\LogManagerInterface;
use litepubl\core\logmanager\LazyFactory;

class StorageTest extends \Codeception\Test\Unit
{
    public function testMe()
    {
        $serializer = new Php();
        $logManager = new LazyFactory(
            function () {
                $manager = $this->prophesize(LogManagerInterface::class);
                return $manager->reveal();
            }
        );

        $storage = new Storage($serializer, $logManager, \Codeception\Configuration::outputDir(), 0666);
        $this->assertInstanceOf(Storage::class, $storage);
        $this->assertInstanceOf(StorageInterface::class, $storage);
        $this->testStorage($storage);

        $pool = new Pool($storage, new FileLocker(\Codeception\Configuration::outputDir() . 'storage.lok'));
        $this->assertInstanceOf(Pool::class, $pool);
        $this->assertInstanceOf(PoolInterface::class, $pool);
        $this->testStorage($pool);
        $pool->commit();

        $pool = new Pool($storage, new FileLocker(\Codeception\Configuration::outputDir() . 'storage.lok'));
        $this->testStorage($pool);

        if (class_exists(\Memcache::class)) {
            $memCache = new \Memcache();
            $memCache->connect('127.0.0.1', 11211);

                $mem = new MemCacheStorage($serializer, $memCache, 'test', 3600);
                $this->testStorage($mem);

            $composite = new Composite($storage, $mem);
                $this->testStorage($composite);
        }
    }

    private function testStorage(StorageInterface $storage)
    {
        $data = new Data();
        $mok = new Mok();
        $data->setData($mok->data);

        $this->assertTrue($storage->save($data));
        $data = new Data();
        $this->assertTrue($storage->load($data));
        $this->assertEquals($mok->data, $data->getData());

        $this->assertTrue($storage->has($data));
        $storage->remove($data);
        $this->assertFalse($storage->has($data));
    }
}
