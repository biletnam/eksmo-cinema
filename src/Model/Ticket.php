<?php
namespace Eksmo\Cinema\Model;

/**
 * @method static Ticket create()
 */
class Ticket extends AbstractModel
{
    public static $_table = 'ticket';

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $sessionId
     * @return $this
     */
    public function setSessionId($sessionId)
    {
        $this->session_id = $sessionId;
        return $this;
    }

    /**
     * @return int
     */
    public function getSessionId()
    {
        return $this->sesion_id;
    }

    /**
     * @param int $place
     * @return $this
     */
    public function setPlace($place)
    {
        $this->place = $place;
        return $this;
    }

    /**
     * @return int
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}