<?php

namespace litepubl\core\storage;

interface StorageAware
{
    public function setStorage(StorageInterface $storage);
}
