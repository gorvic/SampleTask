<?php

namespace FS;

use SplFileObject;

class TextFileReader
{
    private $handle;
    private ?string $row;

    /**
     * @throws Exception
     */
    public function __construct($filename)
    {
        if (!is_file($filename)) {
            throw new \Exception("File $filename does not exist");
        }
        $this->handle = new SplFileObject($filename, "r");
    }

    public function __destruct(){
        $this->close();
    }

    public function read(): ?string
    {
        return !$this->handle->eof() ? $this->handle->fgets() : null ;
    }

    public function getRow(): ?string
    {
        return rtrim($this->row);
    }

    public function close(): void
    {
        $this->handle = null;
    }

}