<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Rate;
use App\Repository\RateRepository;
use Doctrine\ORM\EntityNotFoundException;

class RatesService
{
    /**
     * @var RateRepository
     */
    private $rateRepository;

    /**
     * RatesService constructor.
     * @param RateRepository $rateRepository
     */
    public function __construct(RateRepository $rateRepository)
    {
        $this->rateRepository = $rateRepository;
    }

    /**
     * @return Rate[]
     */
    public function getAll(): array
    {
        return $this->rateRepository->findAll();
    }

    /**
     * @param int $id
     * @return Rate
     * @throws EntityNotFoundException
     */
    public function getRate(int $id): Rate
    {
        $rate = $this->rateRepository->find($id);
        if (!$rate) {
            throw new EntityNotFoundException("Rate with ID {$id} not found");
        }

        return $rate;
    }

    /**
     * @param int $rateId
     * @param float $rateValue
     * @return Rate
     * @throws EntityNotFoundException
     */
    public function setRateValue(int $rateId, float $rateValue): Rate
    {
        $rate = $this->getRate($rateId);
        $rate->setRate($rateValue);
        return $this->rateRepository->save($rate);
    }

    public function deleteRate(int $rateId): void
    {
        $rate = $this->getRate($rateId);
        $this->rateRepository->delete($rate);
    }
}