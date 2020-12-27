<?php

declare(strict_types=1);

namespace App\RatesSources;

/**
 * Declares an dependency which should be implemented by each new rates source.
 */
interface RatesSourceInterface
{
    /**
     * Each configured rates source must provide his unique identifier
     * to avoid issues with different rates for the same currencies in other source.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Obviously, this method should accept date or timestamp,
     * but our demo sources don't provide this option.
     *
     * @throws RatesSourceException
     *
     * @return RateInterface[]
     */
    public function getRates(): array;
}