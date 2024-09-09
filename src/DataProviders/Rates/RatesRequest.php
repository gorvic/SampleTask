<?php

namespace DataProviders\Rates;

use DataProviders\Rates\Drivers\RatesDriverInterface as RatesDriver;

class RatesRequest
{
    private RatesDriver $ratesDriver;
    private ?RatesResponse $ratesResponse;

    public function __construct(string $ratesDriver = 'ExchangeratesApiIo', ?array $config = null)
    {
        $this->setRatesDriver($ratesDriver, $config);
        $this->ratesResponse = null;
    }

    public function getRates(): array
    {
        return $this->request()->getRates();
    }

    public function request(): RatesResponse
    {
        if (!$this->ratesResponse) {
            $this->ratesResponse = $this->getRatesDriver()->request();
        }
        return $this->ratesResponse;
    }

    /**
     * @return RatesDriver
     */
    public function getRatesDriver(): RatesDriver
    {
        return $this->ratesDriver;
    }

    /**
     * @param string $ratesDriver
     * @param array|null $config
     * @return RatesRequest
     */
    public function setRatesDriver(string $ratesDriver, ?array $config): self
    {
        $ratesDriverClass = '\DataProviders\Rates\Drivers\\' . $ratesDriver;
        $this->ratesDriver = new $ratesDriverClass($config);
        return $this;
    }

    public function getRate(string $code, ?int $decimals = null): ?float
    {
        return $this->request()->getRate($code, $decimals);
    }

}