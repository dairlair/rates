<?php

declare(strict_types=1);

namespace App\RatesSources\Providers\Ecb;

use App\RatesSources\RateDto;
use App\RatesSources\RatesDownloader;
use App\RatesSources\RatesSourceException;
use App\RatesSources\RatesSourceInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class EcbRatesSource implements RatesSourceInterface
{
    /**
     * @var string
     */
    private $id;

    private $url;

    /**
     * ECB Software provides rates for EUR currency. But, they can sell their software to UK, thanks Brexit :)
     * After that the base currency will be GBP, obviously.
     *
     * @var string
     */
    private $baseCurrency;

    /**
     * @var RatesDownloader
     */
    private $downloader;

    public function __construct(string $id, string $url, string $baseCurrency, RatesDownloader $downloader)
    {
        $this->id = $id;
        $this->url = $url;
        $this->baseCurrency = $baseCurrency;
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
        return $this->decodeRatesXml($content);
    }

    /**
     * @param string $content
     * @throws RatesSourceException
     *
     * @return RateDto[]
     */
    private function decodeRatesXml(string $content): array
    {
        $decoder = new XmlEncoder();
        $nodes = $decoder->decode($content, 'xml');

        if (!isset($nodes['Cube']['Cube']['Cube'])) {
            throw new RatesSourceException("The response of source {$this->getId()} has broken format");
        }

        $rates = [];
        foreach ($nodes['Cube']['Cube']['Cube'] as $node) {
            $rates[] = new RateDto($this->baseCurrency, $node['@currency'], $node['@rate']);
        }

        return $rates;
    }
}