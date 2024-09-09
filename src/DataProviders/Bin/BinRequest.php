<?php

namespace DataProviders\Bin;

use DataProviders\Bin\Drivers\BinDriverInterface as BinDriver;

class BinRequest
{
    private BinDriver $binDriver;

    public function __construct(string $binDriver = 'BinEmulator', ?array $config = null)
    {
        $this->setBinDriver($binDriver, $config);
    }

    public function request(string $bin): BinResponse
    {
       return $this->getBinDriver()->request($bin);
    }

    /**
     * @return BinDriver
     */
    public function getBinDriver(): BinDriver
    {
        return $this->binDriver;
    }

    /**
     * @param string $binDriver
     * @param array|null $config
     * @return BinRequest
     */
    public function setBinDriver(string $binDriver, ?array $config): self
    {
        $binDriverClass = '\DataProviders\Bin\Drivers\\' . $binDriver;
        $this->binDriver = new $binDriverClass($config);
        return $this;
    }
}