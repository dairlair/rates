<?php

declare(strict_types=1);

namespace App\RatesSources;

use GuzzleHttp\Psr7\Request;
use Lukasoppermann\Httpstatus\Httpstatuscodes;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class RatesDownloader
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Sends the GET request and returns content
     *
     * @param string $url
     *
     * @throws RatesSourceException
     *
     * @return string
     */
    public function download(string $url): string
    {
        $request = new Request('GET', $url);
        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new RatesSourceException("Source is not available");
        }

        if ($response->getStatusCode() !== Httpstatuscodes::HTTP_OK) {
            $message = "Source is broken, HTTP Code: {$response->getStatusCode()}";
            throw new RatesSourceException($message);
        }

        return $response->getBody()->getContents();
    }
}