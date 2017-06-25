<?php

namespace LitePubl\Core\Storage;

trait StorageAwareTrait
{
    protected $storage;

    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;

        if ($this instanceof SaveableInterface) {
                $this->load();
        }
    }
}
