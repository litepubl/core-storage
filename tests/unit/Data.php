<?php

namespace litepubl\tests\storage;

use litepubl\core\storage\Storable;
use litepubl\core\storage\StorableTrait;

class Data implements Storable
{
    use StorableTrait;

    public $mok = [
    's' => 'v',
    'i' => 4,
    'b' => false,
    'f' => 3.14,
    'a' => [
    'q' => 'w',
    ],
    'items' => [
    ['id' => 1],
    ['id' => 2],
    ]
    ];
}
