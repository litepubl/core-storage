<?php
namespace LitePubl\Core\Storage;

class FileLocker implements LockerInterface
{
    private $fileName;
    private $handler;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public function __destruct()
    {
        $this->unlock();
    }

    public function lock(): bool
    {
        if ($this->handler = \fopen($this->fileName, 'w')) {
            if (\flock($this->handler, LOCK_EX | LOCK_NB)) {
                        return true;
            }

            \fclose($this->handler);
            $this->handler = null;
            \chmod($this->fileName, 0666);
        }

        return false;
    }

    public function unlock()
    {
        if ($this->handler) {
            \flock($this->handler, LOCK_UN);
            \fclose($this->handler);
            $this->handler = null;
        }
    }
}
