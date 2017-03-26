<?php
// Here you can initialize variables that will be available to your tests
use Codeception\Util\Autoload;

Autoload::addNamespace('litepubl\tests\storage', __DIR__);
Autoload::addNamespace('litepubl\core\storage', __DIR__ . '/../../src');
Autoload::addNamespace('litepubl\core\logmanager', __DIR__ . '/../../../logmanager/src');