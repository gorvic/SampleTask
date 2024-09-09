<?php

namespace DataProviders\Bin\Drivers;

use DataProviders\Bin\BinResponse;

interface BinDriverInterface
{
    public function __construct(?array $config = null);
    public function request(string $bin): BinResponse;
}