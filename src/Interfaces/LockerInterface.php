<?php
namespace LitePubl\Storage\Interfaces;

interface LockerInterface
{
    public function lock(): bool;
    public function unlock();
}
