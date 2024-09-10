<?php

namespace DataProviders\Rates\Drivers;

use DataProviders\Rates\RatesResponse;
use Http\Request as HttpRequest;
use function str_replace;

class ExchangeratesApiIo implements RatesDriverInterface
{
    private ?string $API_KEY;
    private ?string $URL;

    /**
     * @throws \Exception
     */
    public function __construct(?array $config = null)
    {
        if ($config['API_KEY'] && $config['URL_PATTERN']) {
            $this->setKey($config['API_KEY']);
            $this->setUrl($config['URL_PATTERN']);
        } else {
            throw new \Exception("Cannot obtain rates API key and URL.");
        }
    }

    public function setKey(?string $key = null): self
    {
        if ($key) {
            $this->API_KEY = $key;
        }
        return $this;
    }

    public function setUrl(?string $url = null): self
    {
        if ($url) {
            $this->URL = str_replace('{API_KEY}', $this->API_KEY, $url);
        }
        return $this;
    }

    public function request(): RatesResponse
    {
        $result = new RatesResponse();

        $request = (new HttpRequest())->request('GET', $this->URL);
        // need to check info from a cache
        if ($request->isAccepted() && $response = $request->getJsonContent()) {
            $result
                ->setDate($response['date'])
                ->setRates($response['rates'])
                ->setBaseCurrency($response['base']);
            //store to a cache
        }
        return $result;
    }
}