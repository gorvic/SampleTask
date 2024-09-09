<?php

namespace DataProviders\Rates\Drivers;

use DataProviders\Rates\RatesResponse;

interface RatesDriverInterface
{
    public function __construct(?array $config = null);
    public function request(): RatesResponse;
}