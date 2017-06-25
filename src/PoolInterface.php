<?php

namespace LitePubl\Core\Storage;

interface PoolInterface extends StorageInterface
{
    public function commit(): bool;
    public function getInstalled(): bool;
}
