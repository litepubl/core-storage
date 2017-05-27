<?php

namespace litepubl\core\storage\storables;

interface SaveableAwareInterface
{
    public function getSaveable(): SaveableInterface;
    public function setSaveable(SaveableInterface $saveable);
}
