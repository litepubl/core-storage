<?php

namespace LitePubl\Core\Storage\Storables;

interface SaveableInterface
{
    public function load(): bool;
    public function save(): bool;
    public function lock(): bool;
    public function unlock(): bool;
    public function getLocked(): bool;
}
