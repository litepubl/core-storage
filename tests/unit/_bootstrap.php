<?php
// Here you can initialize variables that will be available to your tests
use Codeception\Util\Autoload;

Autoload::addNamespace('LitePubl\Tests\Storage', __DIR__);
Autoload::addNamespace('LitePubl\Core\Storage', __DIR__ . '/../../src');
Autoload::addNamespace('LitePubl\Core\LogManager', __DIR__ . '/../../../logmanager/src');
