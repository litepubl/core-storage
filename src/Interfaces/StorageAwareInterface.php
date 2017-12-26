<?php

namespace LitePubl\Storage\Interfaces;

interface StorageAwareInterface
{
    public function getStorage(): StorageInterface;
    public function setStorage(StorageInterface $storage);
}
