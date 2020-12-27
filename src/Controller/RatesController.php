<?php

namespace App\Controller;

use App\Service\RatesService;
use Lukasoppermann\Httpstatus\Httpstatuscodes;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Component\HttpFoundation\Request;

class RatesController extends AbstractFOSRestController
{
    private $ratesService;

    public function __construct(RatesService $ratesService)
    {
        $this->ratesService = $ratesService;
    }

    /**
     * @Get("/rates")
     */
    public function index(): View
    {
        $rates = $this->ratesService->getAll();

        return View::create($rates, Httpstatuscodes::HTTP_CREATED);
    }

    /**
     * Updates Rate
     * @Put("/rates/{rateId}")
     */
    public function putRate(int $rateId, Request $request): View
    {
        // Just a wrong way example - how to not validate user input in the controllers :)
        if ($rateValue = (float)$request->get('rate')) {
            $rate = $this->ratesService->setRateValue($rateId, $rateValue);
            return View::create($rate, Httpstatuscodes::HTTP_OK);
        }

        $response = ['code' => Httpstatuscodes::HTTP_BAD_REQUEST, 'message' => 'Rate value is required'];
        return View::create($response, Httpstatuscodes::HTTP_BAD_REQUEST);
    }

    /**
     * Deletes Rate
     * @Delete("/rates/{rateId}")
     */
    public function deleteRate(int $rateId): View
    {
        $this->ratesService->deleteRate($rateId);

        $response = ['code' => Httpstatuscodes::HTTP_ACCEPTED, 'message' => 'Rate deleted successfully'];
        return View::create($response, Httpstatuscodes::HTTP_ACCEPTED);
    }
}
