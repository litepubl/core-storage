<?php

namespace LitePubl\Tests\Storage;

use LitePubl\Core\Storage\Serializer\SerializerInterface;
use LitePubl\Core\Storage\Serializer\JSon;
use LitePubl\Core\Storage\Serializer\Php;
use LitePubl\Core\Storage\Serializer\Serialize;

class SerializerTest extends \Codeception\Test\Unit
{
    public function testMe()
    {
        $this->testSerializer(new Php());
        $this->testSerializer(new Serialize());
        $this->testSerializer(new JSon());
        $this->testSerializer(new JSon(0, true));
    }

    private function testSerializer(SerializerInterface $serializer)
    {
        $data = new Mok();
        $s = $serializer->serialize($data->data);
        $a = $serializer->unserialize($s);
        $this->assertEquals($data->data, $a);

        $fileName = \Codeception\Configuration::outputDir() . '/serializer' . $serializer->getExt();
        $serializer->save($fileName, $data->data);
        $this->assertFileExists($fileName);
        $a = $serializer->load($fileName);
        $this->assertEquals($data->data, $a);
        unlink($fileName);
    }
}
