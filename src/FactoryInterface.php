<?php

namespace litepubl\core\storage;

interface FactoryInterface
{
    public function getStorage(): StorageInterface;
    public function getPool(): PoolInterface;
}
