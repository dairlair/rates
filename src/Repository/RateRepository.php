<?php

namespace App\Repository;

use App\Entity\Currency;
use App\Entity\Rate;
use App\Entity\Source;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rate[]    findAll()
 * @method Rate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rate::class);
    }

    public function createRate(Currency $base, Currency $quote, Source $source, float $rateValue): Rate
    {
        $rate = $this->findOneByCurrenciesAndSource($base, $quote, $source);
        if (!$rate) {
            $rate = $this->createByCurrenciesAndSource($base, $quote, $source);
        }

        $rate->setRate($rateValue);
        $this->_em->persist($rate);
        $this->_em->flush();

        return $rate;
    }

    private function findOneByCurrenciesAndSource(Currency $base, Currency $quote, Source $source): ?Rate
    {
        return $this->findOneBy(['baseCurrency' => $base, 'quoteCurrency' => $quote, 'source' => $source]);
    }

    /**
     * Creates "empty" rate, without rate value.
     *
     * @param Currency $base
     * @param Currency $quote
     * @param Source $source
     * @return Rate
     */
    private function createByCurrenciesAndSource(Currency $base, Currency $quote, Source $source): Rate
    {
        $rate = new Rate();
        $rate->setBaseCurrency($base);
        $rate->setQuoteCurrency($quote);
        $rate->setSource($source);
        return $rate;
    }
}
