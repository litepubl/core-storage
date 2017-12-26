<?php

namespace LitePubl\Tests\Storage;

use LitePubl\Core\Storage\StorageInterface;
use LitePubl\Core\Storage\Storage;
use LitePubl\Core\Storage\Pool;
use LitePubl\Core\Storage\PoolInterface;
use LitePubl\Core\Storage\LockerInterface;
use LitePubl\Core\Storage\FileLocker;
use LitePubl\Core\Storage\MemCacheStorage;
use LitePubl\Core\Storage\Composite;
use LitePubl\Core\Storage\Storables\StorableInterface;
use LitePubl\Core\Storage\Storables\Data;
use LitePubl\Core\Storage\Serializer\SerializerInterface;
use LitePubl\Core\Storage\Serializer\JSon;
use LitePubl\Core\Storage\Serializer\Php;
use LitePubl\Core\Storage\Serializer\Serialize;
use LitePubl\Core\LogManager\LogManagerInterface;
use LitePubl\Core\LogManager\LazyFactory;

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
