<?php
namespace Eksmo\Cinema\Model;

/**
 * @method static Session create()
 */
class Session extends AbstractModel
{
    public static $_table = 'session';

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $hallId
     * @return $this
     */
    public function setCinemaHallId($hallId)
    {
        $this->cinema_hall_id = $hallId;
        return $this;
    }

    /**
     * @return int
     */
    public function getCinemaHallId()
    {
        return $this->cinema_hall_id;
    }

    /**
     * @param int $filmId
     * @return $this
     */
    public function setFilmId($filmId)
    {
        $this->film_id = $filmId;
        return $this;
    }

    /**
     * @return int
     */
    public function getFilmId()
    {
        return $this->film_id;
    }

    /**
     * @param \DateTime $start
     * @return $this
     */
    public function setStart(\DateTime $start)
    {
        $this->start = $start->format('Y-m-d H:i:s');
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return new \DateTime($this->start);
    }

    /**
     * @param \DateTime $stop
     * @return $this
     */
    public function setStop(\DateTime $stop)
    {
        $this->stop = $stop->format('Y-m-d H:i:s');
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStop()
    {
        return new \DateTime($this->stop);
    }
}