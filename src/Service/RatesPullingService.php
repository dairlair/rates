<?php

declare(strict_types=1);

namespace App\Service;

use App\RatesSources\RatesSourceInterface;
use App\Repository\CurrencyRepository;
use App\Repository\RateRepository;
use App\Repository\SourceRepository;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

class RatesPullingService
{
    /** @var CurrencyRepository */
    private $currencyRepository;

    /** @var RateRepository */
    private $rateRepository;

    /** @var SourceRepository */
    private $sourceRepository;

    /** @var RatesSourceInterface[] */
    private $ratesSources;

    /**
     * RatesPullingService constructor.
     * @param CurrencyRepository $currencyRepository
     * @param RateRepository $rateRepository
     * @param SourceRepository $sourceRepository
     * @param RatesSourceInterface[] $ratesSources
     */
    public function __construct(
        CurrencyRepository $currencyRepository,
        RateRepository $rateRepository,
        SourceRepository $sourceRepository,
        RatesSourceInterface ...$ratesSources
    ) {
        $this->currencyRepository = $currencyRepository;
        $this->rateRepository = $rateRepository;
        $this->sourceRepository = $sourceRepository;
        $this->ratesSources = $ratesSources;
    }

    public function sync(SymfonyStyle $io): void
    {
        $io->writeln('Sync started at ' . date('r'));
        foreach ($this->ratesSources as $ratesSource) {
            try {
                $this->syncSource($ratesSource);
                $io->success('The source ' . $ratesSource->getId() . ' synced');
            } catch (Throwable $e) {
                $io->error('Sync failed: ' . $e->getMessage());
            }
        }
        $io->writeln('Sync finished at ' . date('r'));
    }

    private function syncSource(RatesSourceInterface $ratesSource): void
    {
        $source = $this->sourceRepository->findOneByNameOrCreate($ratesSource->getId());
        foreach ($ratesSource->getRates() as $rate) {
            $baseCurrency = $this->currencyRepository->findOneByCodeOrCreate($rate->getBaseCurrency());
            $quoteCurrency = $this->currencyRepository->findOneByCodeOrCreate($rate->getQuoteCurrency());
            $rateValue = $rate->getRateValue();
            $this->rateRepository->createRate($baseCurrency, $quoteCurrency, $source, $rateValue);
        }
    }
}