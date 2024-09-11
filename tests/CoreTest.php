<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use \src\Core;
use \src\Calculate;

require_once __DIR__.'/../vendor/autoload.php';

class CoreTest extends TestCase
{

    public function testCore()
    {
        $this->assertInstanceOf('\src\Core', Core::run());
    }

    public function testGetConfig()
    {
        $this->assertIsArray(Core::run()->getConfig());
        $this->assertIsArray(Core::run()->getConfig('general'));
        $this->assertNull(Core::run()->getConfig('some'));
    }

    public function testGetConfigValue()
    {
        $this->assertIsString(Core::run()->getConfigValue('general', 'BIN_DRIVER'));
        $this->assertIsString(Core::run()->getConfigValue('general', 'EU_MULTIPLIER'));
        $this->assertNull(Core::run()->getConfigValue('general', 'SOME'));
    }

    public function testGetBinRequest()
    {
        $this->assertInstanceOf('DataProviders\Bin\BinRequest', Core::run()->getBinRequest());
    }

    public function testGetRatesRequest()
    {
        $this->assertInstanceOf('DataProviders\Rates\RatesRequest', Core::run()->getRatesRequest(), '');
    }

    public function testGetEurRate()
    {
        $this->assertEquals(1, Core::run()->getRatesRequest()->getRate('EUR'));
    }

    public function testGetUsdRate()
    {
        $this->assertEquals(1.110999, Core::run()->getRatesRequest()->getRate('USD'));
    }

    public function testGetUnknownBin()
    {
        $this->assertNull(Core::run()->getBinRequest()->request('1234')->getCountryEuLocated());
    }

    public function testGetKnownBin()
    {
        $this->assertNotNull(Core::run()->getBinRequest()->request('45717360')->getCountryEuLocated());
    }

    public function testCeiling()
    {
        $this->assertEquals(1.13, (new Calculate())->ceiling(1.123, 2));
        $this->assertEquals(1.12, (new Calculate())->ceiling(1.12, 2));
        $this->assertIsFloat((new Calculate())->ceiling(1.123, 2));
    }

    public function testProcessTransaction()
    {
        $this->assertIsString((new Calculate())->processTransaction(
            json_decode('{"bin":"45717360","amount":"100.00","currency":"EUR"}', true)));
    }

    public function testGetEuCommissionAmount()
    {
        $this->assertEquals(0.46, (new Calculate())->getCommissionAmount(50, 1.110999, true));
    }

    public function testGetNonEuCommissionAmount()
    {
        $this->assertEquals(0.91, (new Calculate())->getCommissionAmount(50, 1.110999, false));
    }

    public function testGlobalCalculation()
    {
        $this->assertIsString((new Calculate())->getCommissions(__DIR__ . '/../output.txt'));
    }
}