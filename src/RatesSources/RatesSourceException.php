<?php

declare(strict_types=1);

namespace App\RatesSources;

use Exception;

/**
 * This exception must be thrown when the rates retrieving from external source failed
 * owing to some source-related issues (like an missing/changed URL, changed API response format, etc...).
 */
class RatesSourceException extends Exception
{
}