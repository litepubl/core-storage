<?php

namespace LitePubl\Storage\Interfaces;

interface SaveableInterface
{
    public function load(): bool;
    public function save(): bool;
    public function lock(): bool;
    public function unlock(): bool;
    public function getLocked(): bool;
}
