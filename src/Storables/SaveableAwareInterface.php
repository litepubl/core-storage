<?php

namespace LitePubl\Core\Storage\Storables;

interface SaveableAwareInterface
{
    public function getSaveable(): SaveableInterface;
    public function setSaveable(SaveableInterface $saveable);
}
