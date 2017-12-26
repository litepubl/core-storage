<?php

namespace LitePubl\Storage\Interfaces;

interface SaveableAwareInterface
{
    public function getSaveable(): SaveableInterface;
    public function setSaveable(SaveableInterface $saveable);
}
