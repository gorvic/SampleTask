<?php

namespace src;

use DataProviders\Bin\BinRequest;
use DataProviders\Rates\RatesRequest;

class Core
{
    private static ?Core $instance = null;
    private ?array $environment = null;
    private array $config;
    private BinRequest $binRequest;
    private RatesRequest $ratesRequest;

    protected function __construct()
    {
        $this->init();
    }

    /**
     * @throws \Exception
     */
    private function init(): void
    {
        $this->environment = \getenv();
        $this->initConfig();
        $this->initBinRequest();
        $this->initRatesRequest();
    }

    /**
     * @throws \Exception
     */
    private function initConfig(): void
    {

        //$conf_fn = $this->environment['APP_ENV'] == 'testing' ? 'config-testing.ini' : 'config.ini';
        $conf_fn = __DIR__ . '/../' . 'config.ini';

        if (is_file($conf_fn) &&
            ($config = parse_ini_file($conf_fn, true))) {
            $this->config = $config;
        } else {
            throw new \Exception("Cannot obtain and parse config file.");
        }
    }

    private function initBinRequest(): void
    {
        $driver = $this->config['general']['BIN_DRIVER'];
        if ($driver &&
            is_file(__DIR__ . '/DataProviders/Bin/Drivers/' . $driver . '.php')) {
            $this->binRequest = new BinRequest($driver, $this->config[$driver]);
        } else {
            throw new \Exception("Cannot initialize bin driver.");
        }
    }

    private function initRatesRequest(): void
    {
        $driver = $this->config['general']['RATES_DRIVER'];
        if ($driver &&
            is_file(__DIR__ . '/DataProviders/Rates/Drivers/' . $driver . '.php')) {
            $this->ratesRequest = new RatesRequest($driver, $this->config[$driver]);
        } else {
            throw new \Exception("Cannot initialize rates driver.");
        }
    }

    public static function run(): self
    {
        if (!self::$instance) {
            self::$instance = new static();

        }
        return self::$instance;
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public function getConfig(?string $section = null): ?array
    {
        return $section ? ($this->config[$section] ?? null) : $this->config;
    }

    public function getConfigValue(string $section, string $key): ?string
    {
        return $this->config[$section][$key] ?? null;
    }

    public function getBinRequest(): BinRequest
    {
        return $this->binRequest;
    }

    public function getRatesRequest(): RatesRequest
    {
        return $this->ratesRequest;
    }

    protected function __clone()
    {
    }
}