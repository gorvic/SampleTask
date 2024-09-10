<?php

namespace src;

use FS\TextFileReader;

class Calculate
{
    public function getCommissions(string $data_file): string
    {
        $result = '';
        try {
            $reader = new TextFileReader($data_file);
            while ($row = $reader->read()) {
                if ($transaction = json_decode($row, true)) {
                    $result .= $this->processTransaction($transaction);
                }
            }
            $reader->close();
        } catch (\Exception $exception) {
            echo $exception->getMessage() . "\n";
        }
        return $result;
    }

    public function processTransaction(array $transaction): string
    {
        $out = "";
        $transaction['rate'] = $transaction['currency'] == "EUR"
            ? 1
            : Core::run()->getRatesRequest()->getRate($transaction['currency']);
        if ($transaction['rate']) {
            $bin = Core::run()->getBinRequest()->request($transaction['bin']);
            if ($bin->getCountryEuLocated() !== null) {
                $transaction['commission'] = $this->getCommissionAmount(
                    $transaction['amount'], $transaction['rate'], $bin->getCountryEuLocated());
                //$out = $this->out_ex($transaction, $transaction['commission'] . " EUR");
                $out = $this->out($transaction, $transaction['commission']);
            } else {
                //$out = $this->out_ex($transaction, "cannot be checked by BIN");
                $out = $this->out($transaction, "NULL");
            }
        } else {
            //$out = $this->out_ex($transaction, "currency rate is unknown");
            $out = $this->out($transaction, NULL);
        }
        return $out;
    }

    public function getCommissionAmount(float $amount, float $rate, bool $isEU): string
    {
        $multiplier = $isEU
            ? Core::run()->getConfigValue('general', 'EU_MULTIPLIER')
            : Core::run()->getConfigValue('general', 'NON_EU_MULTIPLIER');
        return sprintf('%.2f', $this->ceiling($amount / $rate * $multiplier, 2));
    }

    public function ceiling(float $value, ?int $decimals = null): ?float
    {
        $pow = $decimals ? 10 ** $decimals : 0;
        return $pow ? ceil((float)$value * $pow . '') / $pow : ceil($value . '');
    }

    private function out(array $transaction, ?string $commission): string
    {
        return $commission . PHP_EOL;
    }

    private function out_ex(array $transaction, ?string $commission): string
    {
        return "'{$transaction['bin']}' {$transaction['amount']} {$transaction['currency']}: {$commission}";
    }

}