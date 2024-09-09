<?php

namespace src;

use FS\TextFileReader;

class Calculate
{
    public static function commissions(string $data_file): void
    {
        try {
            $reader = new TextFileReader($data_file);
            while ($row = $reader->read()) {
                if ($transaction = json_decode($row, true)) {
                    echo self::processTransaction($transaction);
                }
            }
            $reader->close();
        } catch (\Exception $exception) {
            echo $exception->getMessage() . "\n";
        }

    }

    public static function processTransaction(array $transaction): string
    {
        $out = "";
        $transaction['rate'] = $transaction['currency'] == "EUR"
            ? 1
            : Core::run()->getRatesRequest()->getRate($transaction['currency']);
        if ($transaction['rate']) {
            $bin = Core::run()->getBinRequest()->request($transaction['bin']);
            if (!is_null($bin->getCountryEuLocated())) {
                $transaction['commission'] = self::getCommissionAmount(
                    $transaction['amount'], $transaction['rate'], $bin->getCountryEuLocated());
                //$out = self::out_ex($transaction, $transaction['commission'] . " EUR");
                $out = self::out($transaction, $transaction['commission']);
            } else {
                //$out = self::out_ex($transaction, "cannot be checked by BIN");
                $out = self::out($transaction, "NULL");
            }
        } else {
            //$out = self::out_ex($transaction, "currency rate is unknown");
            $out = self::out($transaction, "NULL");
        }
        return $out;
    }

    public static function getCommissionAmount(float $amount, float $rate, bool $isEU): string
    {
        $multiplier = $isEU
            ? Core::run()->getConfigValue('general', 'EU_MULTIPLIER')
            : Core::run()->getConfigValue('general', 'NON_EU_MULTIPLIER');
        return sprintf('%.2f', self::ceiling($amount / $rate * $multiplier, 2));
    }

    public static function ceiling(float $value, ?int $decimals = null): ?float
    {
        $pow = $decimals ? 10 ** $decimals : 0;
        return $pow ? ceil((float)$value * $pow . '') / $pow : ceil($value . '');
    }

    private static function out(array $transaction, string $commission): string
    {
        return $commission . PHP_EOL;
    }

    private static function out_ex(array $transaction, string $commission): string
    {
        return "'{$transaction['bin']}' {$transaction['amount']} {$transaction['currency']}: {$commission}";
    }

}