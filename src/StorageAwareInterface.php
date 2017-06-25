<?php

namespace LitePubl\Core\Storage;

interface StorageAwareInterface
{
    public function getStorage(): StorageInterface;
    public function setStorage(StorageInterface $storage);
}
