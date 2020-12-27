<?php

declare(strict_types=1);

namespace App\RatesSources\Providers\Coindesk;

use App\RatesSources\RateDto;
use App\RatesSources\RatesDownloader;
use App\RatesSources\RatesSourceException;
use App\RatesSources\RatesSourceInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class CoindeskRatesSource implements RatesSourceInterface
{
    public const BASE_CURRENCY = 'BTC';
    public const QUOTE_CURRENCY = 'USD';

    /**
     * @var string
     */
    private $id;

    private $url;

    /**
     * @var RatesDownloader
     */
    private $downloader;

    public function __construct(string $id, string $url, RatesDownloader $downloader)
    {
        $this->id = $id;
        $this->url = $url;
        $this->downloader = $downloader;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getRates(): array
    {
        $content = $this->downloader->download($this->url);
        return $this->decodeRatesJson($content);
    }

    /**
     * @param string $content
     * @throws RatesSourceException
     *
     * @return RateDto[]
     */
    private function decodeRatesJson(string $content): array
    {
        $decoder = new JsonEncoder();
        $nodes = $decoder->decode($content, 'json');

        if (!isset($nodes['bpi'])) {
            throw new RatesSourceException("The response of source {$this->getId()} has broken format");
        }

        $latestRate = (float)array_pop($nodes['bpi']);

        return [new RateDto(self::BASE_CURRENCY, self::QUOTE_CURRENCY, $latestRate)];
    }
}