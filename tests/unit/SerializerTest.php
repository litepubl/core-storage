<?php

namespace litepubl\tests\storage;

use litepubl\core\storage\serializer\SerializerInterface;
use litepubl\core\storage\serializer\JSon;
use litepubl\core\storage\serializer\Php;
use litepubl\core\storage\serializer\Serialize;

class SerializerTest extends \Codeception\Test\Unit
{
    private $data = [
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

    public function testMe()
    {
        $this->testSerializer(new Php());
        $this->testSerializer(new Serialize());
        $this->testSerializer(new JSon());
        $this->testSerializer(new JSon(0, true));
    }

    private function testSerializer(SerializerInterface $serializer)
    {
        $s = $serializer->serialize($this->data);
        $data = $serializer->unserialize($s);
        $this->assertEquals($this->data, $data);

        $fileName = \Codeception\Configuration::outputDir() . '/serializer' . $serializer->getExt();
        $serializer->save($fileName, $this->data);
        $this->assertFileExists($fileName);
        $data = $serializer->load($fileName);
        $this->assertEquals($this->data, $data);
        unlink($fileName);
    }
}
