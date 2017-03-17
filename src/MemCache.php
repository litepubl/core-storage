<?php

namespace litepubl\core\storage;

use litepubl\core\storage\serializer\SerializerInterface;

class MemCache implements StorageInterface
{
    private $serializer;
    private $memcache;
    private $prefix;
    private $lifetime;

    public function __construct(SerializerInterface $serializer, \MemCache $memCache, string $prefix, int $lifetime = 3600)
    {
        $this->serializer = $serializer;
        $this->memcache = $memCache;
        $this->prefix = $prefix;
        $this->lifetime = $lifetime;
    }

    public function load(Storable $storable): bool
    {
        try {
            if ($data = $this->loadData($this->getFileName($storable) . $this->serializer->getExt())) {
                $storable->setData($data);
                return true;
            }
        } catch (\Throwable $e) {
            $this->logManager->logException($e);
        }

        return false;
    }

    public function save(Storable $storable): bool
    {
        return $this->saveData($this->getFileName($storable), $this->serializer->getExt(), $storable->getData());
    }

    public function getFilename(Storable $storable): string
    {
        return $this->prefix . $storable->getBaseName();
    }

    public function loadData(string $fileName): ? array
    {
        if ($s = $this->memcache->get($fileName)) {
            return $this->serializer->unserialize($s);
        }

        return null;
    }

    public function saveData(string $fileName, array $data): bool
    {
        return $this->memcache->set($fileName, $this->serializer->serialize($data), false, $this->lifetime);
    }

    public function remove(Storable $storable ): bool
    {
        $fileName = $this->getFileName($storable);
        return $this->removeFile($fileName . $this->serializer->getExt());
    }

    public function removeFile(string $fileName): bool
    {
        return $this->memcache->delete($fileName);
    }
}
