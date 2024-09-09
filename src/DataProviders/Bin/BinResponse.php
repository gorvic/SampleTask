<?php

namespace DataProviders\Bin;

use stdClass;

class BinResponse extends stdClass
{
    private ?string $scheme = null;
    private ?string $type = null;
    private ?string $brand = null;
    private ?string $bank = null;
    private ?string $country_code = null;
    private ?string $country_name = null;
    private ?bool $country_eu_located = null;
    private ?string $country_currency = null;

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function setScheme(?string $scheme): BinResponse
    {
        $this->scheme = $scheme;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(?string $type): BinResponse
    {
        $this->type = $type;
        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): BinResponse
    {
        $this->brand = $brand;
        return $this;
    }

    public function getBank(): ?string
    {
        return $this->bank;
    }

    public function setBank(?string $bank): BinResponse
    {
        $this->bank = $bank;
        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    public function setCountryCode(?string $country_code): BinResponse
    {
        $this->country_code = $country_code;
        return $this;
    }

    public function getCountryName(): ?string
    {
        return $this->country_name;
    }

    public function setCountryName(?string $country_name): BinResponse
    {
        $this->country_name = $country_name;
        return $this;
    }

    public function getCountryEuLocated(): ?bool
    {
        return $this->country_eu_located;
    }

    public function setCountryEuLocated(?string $country_code): BinResponse
    {
        $this->country_eu_located = $this->isCountryEuLocated($country_code);
        return $this;
    }

    private function isCountryEuLocated(?string $code): ?bool
    {
        $eu_codes = [
            'AT',
            'BE',
            'BG',
            'CY',
            'CZ',
            'DE',
            'DK',
            'EE',
            'ES',
            'FI',
            'FR',
            'GR',
            'HR',
            'HU',
            'IE',
            'IT',
            'LT',
            'LU',
            'LV',
            'MT',
            'NL',
            'PO',
            'PT',
            'RO',
            'SE',
            'SI',
            'SK'
        ];
        return in_array($code, $eu_codes) ? true : ($code ? false : null);
    }

    public function getCountryCurrency(): ?string
    {
        return $this->country_currency;
    }

    public function setCountryCurrency(?string $country_currency): BinResponse
    {
        $this->country_currency = $country_currency;
        return $this;
    }


}