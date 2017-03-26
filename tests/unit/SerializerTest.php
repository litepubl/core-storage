<?php

namespace litepubl\tests\storage;

use litepubl\core\storage\serializer\SerializerInterface;
use litepubl\core\storage\serializer\JSon;
use litepubl\core\storage\serializer\Php;
use litepubl\core\storage\serializer\Serialize;

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
$data = new Data();
        $s = $serializer->serialize($data->mok);
        $a = $serializer->unserialize($s);
        $this->assertEquals($data->mok, $a);

        $fileName = \Codeception\Configuration::outputDir() . '/serializer' . $serializer->getExt();
        $serializer->save($fileName, $data->mok);
        $this->assertFileExists($fileName);
        $a = $serializer->load($fileName);
        $this->assertEquals($data->mok, $a);
        unlink($fileName);
    }
}
