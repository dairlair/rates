<?php

declare(strict_types=1);

namespace App\RatesSources;

interface RateInterface
{
    /**
     * Returns any symbol identifier, will be mapped to app entity later
     *
     * @return string
     */
    public function getBaseCurrency(): string;

    /**
     * Returns any symbol identifier, will be mapped to app entity later
     *
     * @return string
     */
    public function getQuoteCurrency(): string;

    /**
     * Just a rate. For demo-use we don't use bcmath or something else, just a flot
     * with some side-effects owing to binary presentation of float in PHP.
     *
     * @return float
     */
    public function getRateValue(): float;
}