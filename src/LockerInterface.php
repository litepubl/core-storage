<?php
namespace litepubl\core\storage;

interface LockerInterface
{
    public function lock(): bool;
    public function unlock();
}
