<?php
namespace Eksmo\Cinema\Controller\Request;

use Eksmo\Cinema\Exception\RestException as RE;

/**
 * @method int getSession
 * @method string getPlaces
 */
class BuyTicketsRequest extends AbstractApiRequest
{
    /**
     * @var int[]
     */
    private $places = array();

    public function validateBusiness()
    {
        $session = $this->app->getDbManager()->getSession($this->getSession());
        if (!$session) {
            throw RE::error('InvalidSession');
        }

        $maxPlaces = $this->app->getDbManager()->getMaxPlacesForSession($this->getSession());
        if (!$maxPlaces) {
            throw RE::error('InvalidSession');
        }

        $placesStr = $this->getPlaces();
        foreach (explode(',', $placesStr) as $place) {
            $place = intval(trim($place));

            if ($place > 0 && $place <= $maxPlaces) {
                $this->places[] = $place;
            } else {
                throw RE::error('InvalidPlaces');
            }
        }
        $this->places = array_unique($this->places);
    }

    /**
     * @return int[]
     */
    public function getPlacesArray()
    {
        return $this->places;
    }
}