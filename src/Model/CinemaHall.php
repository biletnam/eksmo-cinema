<?php
namespace Eksmo\Cinema\Model;

/**
 * @method static CinemaHall create()
 */
class CinemaHall extends AbstractModel
{
    public static $_table = 'cinema_hall';

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $cinemaId
     * @return $this
     */
    public function setCinemaId($cinemaId)
    {
        $this->cinema_id = $cinemaId;
        return $this;
    }

    /**
     * @return int
     */
    public function getCinemaId()
    {
        return $this->cinema_id;
    }

    /**
     * @param int $num
     * @return $this
     */
    public function setHallNum($num)
    {
        $this->hall_num = $num;
        return $this;
    }

    /**
     * @return int
     */
    public function getHallNum()
    {
        return $this->hall_num;
    }

    /**
     * @param int $places
     * @return $this
     */
    public function setPlacesCnt($places)
    {
        $this->places = $places;
        return $this;
    }

    /**
     * @return int
     */
    public function getPlacesCnt()
    {
        return $this->places;
    }
}