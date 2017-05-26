<?php

namespace litepubl\core\storage;

interface StorageAwareInterface
{
    public function getStorage(): StorageInterface;
    public function setStorage(StorageInterface $storage);
}
