<?php
/**
 * Lite Publisher CMS
 *
 * @copyright 2010 - 2016 Vladimir Yushko http://litepublisher.com/ http://litepublisher.ru/
 * @license   https://github.com/litepubl/cms/blob/master/LICENSE.txt MIT
 * @link      https://github.com/litepubl\cms
 * @version   7.07
  */

namespace litepubl\core\storage;

class Cache implements StorageInterface
{
    protected $storage;
    protected $cache;

    public function __construct(StorageInterface $storage, StorageInterface $cache) 
    {
            $this->storage = $storage;
        $this->cache = $cacheSttorage;
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

    public function loadData(string $fileName): ? array
    {
        $result = $this->cache->loadData($fileName);
        if (!$result) {
                $result = $this->storage->loadData($fileName);
            if ($result) {
                $this->cache->saveData($fileName, $result);
            }
        }

        return $result;
    }

    public function saveData(string $fileName, array $data): bool
    {
        $this->cache->saveData($fileName, $data);
        return $this->storage->saveData($fileName, $data);
    }

    public function remove(Storable $storable ): bool
    {
        $this->cache->remove($storable);
        return $this->storage->remove($storable);
    }

    public function removeFile(string $fileName): bool
    {
        $this->cache->removeFile($fileName);
        return $this->storage->removeFile($fileName);
    }
}
