<?php

namespace LitePubl\Storage\Storage;

use LitePubl\Storage\Interfaces\SaveableInterface;
use LitePubl\Storage\Interfaces\StorageInterface;

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
