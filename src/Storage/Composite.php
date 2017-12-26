<?php

namespace LitePubl\Storage\Storage;

use LitePubl\Storage\Interfaces\StorableInterface;
use LitePubl\Storage\Interfaces\StorageInterface;

class Composite implements StorageInterface
{
    protected $storage;
    protected $cache;

    public function __construct(StorageInterface $storage, StorageInterface $cache)
    {
            $this->storage = $storage;
        $this->cache = $cache;
    }

    public function has(StorableInterface $storable): bool
    {
        return $this->cache->has($storable) || $this->storage->has($storable);
    }

    public function load(StorableInterface $storable): bool
    {
        $result = $this->cache->load($storable);
        if (!$result) {
                $result = $this->storage->load($storable);
            if ($result) {
                $this->cache->save($storable);
            }
        }

        return $result;
    }

    public function save(StorableInterface $storable): bool
    {
        $this->cache->save($storable);
        return $this->storage->save($storable);
    }

    public function getFilename(StorableInterface $storable): string
    {
        return $this->storage->getFileName($storable);
    }

    public function remove(StorableInterface $storable): bool
    {
        $this->cache->remove($storable);
        return $this->storage->remove($storable);
    }
}
