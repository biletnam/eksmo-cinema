<?php
namespace Eksmo\Cinema\Controller;

use Eksmo\Cinema\Controller\Request\BuyTicketsRequest;
use Eksmo\Cinema\Controller\Request\GetCinemaScheduleRequest;
use Eksmo\Cinema\Controller\Request\GetFilmScheduleRequest;
use Eksmo\Cinema\Controller\Request\GetSessionPlacesRequest;
use Eksmo\Cinema\Controller\Request\RejectTicketsRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends AbstractController
{
    /**
     * @return JsonResponse
     */
    public function getCinemaScheduleAction()
    {
        /** @var GetCinemaScheduleRequest $request */
        $request = $this->getRequest(GetCinemaScheduleRequest::getClass());

        $dbManager = $this->app->getDbManager();
        $schedule = $dbManager->getScheduleForCinema($request->getCinemaId(), $request->getHall());

        return $this->json($schedule);
    }

    /**
     * @return JsonResponse
     */
    public function getFilmScheduleAction()
    {
        /** @var GetFilmScheduleRequest $request */
        $request = $this->getRequest(GetFilmScheduleRequest::getClass());

        $dbManager = $this->app->getDbManager();
        $schedule = $dbManager->getScheduleForFilm($request->getFilmId());

        return $this->json($schedule);
    }

    /**
     * @return JsonResponse
     */
    public function buyTicketsAction()
    {
        /** @var BuyTicketsRequest $request */
        $request = $this->getRequest(BuyTicketsRequest::getClass());

        $code = $this->app->getDbManager()->createTickets($request->getSession(), $request->getPlacesArray());

        return $this->json(array(
            'code' => $code
        ));
    }

    /**
     * @return JsonResponse
     */
    public function getSessionPlacesAction()
    {
        /** @var GetSessionPlacesRequest $request */
        $request = $this->getRequest(GetSessionPlacesRequest::getClass());

        $dbManager = $this->app->getDbManager();
        $maxPlaces = $dbManager->getMaxPlacesForSession($request->getSessionId());
        $purchasedPlaces = $dbManager->getPurchasedPlaces($request->getSessionId());

        return $this->json(array(
            'total'     => $maxPlaces,
            'purchased' => $purchasedPlaces
        ));
    }

    /**
     * @return JsonResponse
     */
    public function rejectTicketsAction()
    {
        /** @var RejectTicketsRequest $request */
        $request = $this->getRequest(RejectTicketsRequest::getClass());

        $tickets = $request->getTickets();
        $ids = $this->app->getDbManager()->deleteTickets($tickets);

        return $this->json(array(
            'success' => $ids
        ));
    }
}