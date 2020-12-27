<?php

declare(strict_types=1);

namespace App\RatesSources;

use DateTimeInterface;

class RateDto implements RateInterface
{
    /**
     * @var string
     */
    private $baseCurrency;

    /**
     * @var string
     */
    private $quoteCurrency;

    /**
     * @var float
     */
    private $rateValue;

    /**
     * RateDto constructor.
     * @param string $baseCurrency
     * @param string $quoteCurrency
     * @param float $rateValue
     */
    public function __construct(string $baseCurrency, string $quoteCurrency, float $rateValue)
    {
        $this->baseCurrency = $baseCurrency;
        $this->quoteCurrency = $quoteCurrency;
        $this->rateValue = $rateValue;
    }

    /**
     * @return string
     */
    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }

    /**
     * @return string
     */
    public function getQuoteCurrency(): string
    {
        return $this->quoteCurrency;
    }

    /**
     * @return float
     */
    public function getRateValue(): float
    {
        return $this->rateValue;
    }
}