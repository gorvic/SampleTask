<?php

namespace DataProviders\Bin\Drivers;

use DataProviders\Bin\BinResponse;
use DataProviders\Bin\Drivers\BinDriverInterface;

class BinEmulator implements BinDriverInterface
{

    public function __construct(?array $config = null)
    {
    }

    public function request(string $bin): BinResponse
    {
        $data = [
            '45717360' => '{"number":{},"scheme":"visa","type":"debit","brand":"Visa Classic/Dankort","country":{"numeric":"208","alpha2":"DK","name":"Denmark","emoji":"🇩🇰","currency":"DKK","latitude":56,"longitude":10},"bank":{"name":"Jyske Bank A/S"}}',
            '516793' => '{"number":{},"scheme":"mastercard","type":"debit","brand":"Debit Mastercard","country":{"numeric":"440","alpha2":"LT","name":"Lithuania","emoji":"🇱🇹","currency":"EUR","latitude":56,"longitude":24},"bank":{"name":"Swedbank Ab"}}',
            '45417360' => '{"number":{},"scheme":"visa","type":"credit","brand":"Visa Classic","country":{"numeric":"392","alpha2":"JP","name":"Japan","emoji":"🇯🇵","currency":"JPY","latitude":36,"longitude":138},"bank":{"name":"Credit Saison Co., Ltd."}}',
            '4745030' => '{"number":{},"scheme":"visa","type":"debit","brand":"Visa Classic","country":{"numeric":"440","alpha2":"LT","name":"Lithuania","emoji":"🇱🇹","currency":"EUR","latitude":56,"longitude":24},"bank":{"name":"Uab Finansines Paslaugos Contis"}}',
            '41417360' => '{"number":null,"country":{},"bank":{}}',
            'unknown' => '{"number":null,"country":{},"bank":{}}'
        ];

        $result = new BinResponse();
        $response = json_decode(array_key_exists($bin, $data) ? $data[$bin] : $data['unknown'], true);
        $result
            ->setScheme($response['scheme'])
            ->setType($response['type'])
            ->setBrand($response['brand'])
            ->setBank($response['bank']["name"])
            ->setCountryCode($response['country']["alpha2"])
            ->setCountryName($response['country']["name"])
            ->setCountryEuLocated($response['country']["alpha2"])
            ->setCountryCurrency($response['country']["currency"]);
        return $result;
    }
}