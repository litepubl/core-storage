<?php

namespace litepubl\core\storage;

interface SaveableAwareInterface
{
    public function getSaveable(): SaveableInterface;
    public function setSaveable(SaveableInterface $saveable);
}
