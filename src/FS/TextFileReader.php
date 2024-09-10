<?php

namespace FS;

use Exception;
use SplFileObject;

class TextFileReader
{
    private ?SplFileObject $handle;

    /**
     * @throws Exception
     */
    public function __construct($filename)
    {
        if (!is_file($filename)) {
            throw new Exception("File $filename does not exist");
        }
        $this->handle = new SplFileObject($filename, "r");
    }

    public function __destruct()
    {
        $this->close();
    }

    public function close(): void
    {
        $this->handle = null;
    }

    public function read(): ?string
    {
        return !$this->handle->eof() ? rtrim($this->handle->fgets()) : null;
    }

}