<?php

namespace LitePubl\Storage\Interfaces;

interface PoolInterface extends StorageInterface
{
    public function commit(): bool;
    public function getInstalled(): bool;
}
