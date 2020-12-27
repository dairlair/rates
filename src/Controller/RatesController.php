<?php

namespace App\Controller;

use App\Repository\RateRepository;
use Lukasoppermann\Httpstatus\Httpstatuscodes;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;

class RatesController extends AbstractFOSRestController
{
    private $rateRepository;

    public function __construct(RateRepository $rateRepository)
    {
        $this->rateRepository = $rateRepository;
    }

    /**
     * @Get("/rates")
     */
    public function index(): View
    {
        $rates = $this->rateRepository->findAll();

        return View::create($rates, Httpstatuscodes::HTTP_CREATED);
    }
}
