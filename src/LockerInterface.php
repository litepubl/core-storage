<?php
namespace LitePubl\Core\Storage;

interface LockerInterface
{
    public function lock(): bool;
    public function unlock();
}
