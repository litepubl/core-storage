<?php

namespace LitePubl\Core\Storage;

use LitePubl\Core\Storage\Storables\StorableInterface;
use LitePubl\Core\Storage\Serializer\SerializerInterface;

class MemCacheStorage implements StorageInterface
{
    protected $serializer;
    protected $memcache;
    protected $prefix;
    protected $lifetime;

    public function __construct(SerializerInterface $serializer, \MemCache $memCache, string $prefix, int $lifetime = 3600)
    {
        $this->serializer = $serializer;
        $this->memcache = $memCache;
        $this->prefix = $prefix;
        $this->lifetime = $lifetime;
    }

    public function getFilename(StorableInterface $storable): string
    {
        return $this->prefix . $storable->getBaseName() . $this->serializer->getExt();
    }

    public function has(StorableInterface $storable): bool
    {
        return !empty($this->memcache->get($this->getFileName($storable)));
    }
    public function load(StorableInterface $storable): bool
    {
            $fileName = $this->getFileName($storable);
        if ($s = $this->memcache->get($fileName)) {
            $data = $this->serializer->unserialize($s);
            if ($data) {
                $storable->setData($data);
                return true;
            }
        }

        return false;
    }

    public function save(StorableInterface $storable): bool
    {
        return $this->memcache->set($this->getFileName($storable), $this->serializer->serialize($storable->getData()), false, $this->lifetime);
    }

    public function remove(StorableInterface $storable): bool
    {
        return $this->memcache->delete($this->getFileName($storable));
    }
}
