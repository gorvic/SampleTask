<?php

namespace DataProviders\Rates;

use stdClass;
use function array_key_exists;

class RatesResponse extends stdClass
{
    private string $base_currency = '';
    private string $date = '';
    private array $rates = [];

    public function getBaseCurrency(): string
    {
        return $this->base_currency;
    }

    public function setBaseCurrency(string $base_currency): RatesResponse
    {
        $this->base_currency = $base_currency;
        return $this;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): RatesResponse
    {
        $this->date = $date;
        return $this;
    }

    public function getRates(): array
    {
        return $this->rates;
    }

    public function setRates(array $rates): RatesResponse
    {
        $this->rates = $rates;
        return $this;
    }

    public function getRate(string $code): ?float
    {
        return array_key_exists($code, $this->rates) && (float) $this->rates[$code] > 0
            ? $this->rates[$code]
            : null ;
    }

    public function setRate(string $code, float $value): RatesResponse
    {
        $this->rates[$code] = $value;
        return $this;
    }
}
