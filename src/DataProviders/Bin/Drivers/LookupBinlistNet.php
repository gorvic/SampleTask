<?php

declare(strict_types=1);

namespace DataProviders\Bin\Drivers;

use DataProviders\Bin\BinResponse;
use Http\Request as HttpRequest;
use function str_replace;

class LookupBinlistNet implements BinDriverInterface
{
    private ?string $URL_PATTERN;

    /**
     * @throws \Exception
     */
    public function __construct(?array $config = null)
    {
        if ($config['URL_PATTERN']) {
            $this->URL_PATTERN = $config['URL_PATTERN'];
        } else {
            throw new \Exception("Cannot obtain BIN provider URL.");
        }
    }

    public function request(string $bin): BinResponse
    {
        $request = (new HttpRequest())->request('GET', str_replace('{BIN_CODE}', $bin, $this->URL_PATTERN));
        $result = new BinResponse();
        if ($request->isAccepted() && $response = $request->getJsonContent()) {
            $result
                ->setScheme($response['scheme'])
                ->setType($response['type'])
                ->setBrand($response['brand'])
                ->setBank($response['bank']["name"])
                ->setCountryCode($response['country']["alpha2"])
                ->setCountryName($response['country']["name"])
                ->setCountryEuLocated($response['country']["alpha2"])
                ->setCountryCurrency($response['country']["currency"]);
        } else {
            // need to get info from some cache
        }
        return $result;
    }
}