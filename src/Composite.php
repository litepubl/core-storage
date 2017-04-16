<?php
namespace litepubl\core\storage;

class Composite implements StorageInterface
{
    protected $storage;
    protected $cache;

    public function __construct(StorageInterface $storage, StorageInterface $cache)
    {
            $this->storage = $storage;
        $this->cache = $cache;
    }

    public function has(Storable $storable): bool
    {
        return $this->cache->has($storable) || $this->storage->has($storable);
    }

    public function load(Storable $storable): bool
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

    public function save(Storable $storable): bool
    {
        $this->cache->save($storable);
        return $this->storage->save($storable);
    }

    public function getFilename(Storable $storable): string
    {
        return $this->storage->getFileName($storable);
    }

    public function remove(Storable $storable): bool
    {
        $this->cache->remove($storable);
        return $this->storage->remove($storable);
    }
}
