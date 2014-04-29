<?php
namespace Eksmo\Cinema\Manager;

use Doctrine\DBAL\Connection;
use Eksmo\Cinema\Model\Session;
use Eksmo\Cinema\Model\Ticket;
use Eksmo\Cinema\Exception\RestException as RE;

class DbManager
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @param int $filmId
     * @return array
     */
    public function getScheduleForFilm($filmId)
    {
        $sql = <<<SQL
SELECT
    s.id AS session_id,
    c.id AS cinema_id,
    ch.hall_num AS hall_num,
    c.name AS cinema_name,
    s.start,
    s.stop
FROM
    session s LEFT JOIN
    cinema_hall ch ON s.cinema_hall_id = ch.id LEFT JOIN
    cinema c ON ch.cinema_id = c.id
WHERE
    s.film_id = :film_id
SQL;

        return $this->db->executeQuery($sql, array('film_id' => $filmId))->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $cinemaId
     * @param int $hallNum
     * @return array
     */
    public function getScheduleForCinema($cinemaId, $hallNum = null)
    {
        $sql = <<<SQL
SELECT
    s.id AS session_id,
    c.id AS cinema_id,
    ch.hall_num AS hall_num,
    f.id AS film_id,
    c.name AS cinema_name,
    f.title AS film_title,
    s.start,
    s.stop
FROM
    cinema_hall ch LEFT JOIN
    session s ON s.cinema_hall_id = ch.id LEFT JOIN
    cinema c ON ch.cinema_id = c.id LEFT JOIN
    film f ON s.film_id = f.id
WHERE
    ch.cinema_id = :cinema_id AND
    s.id IS NOT NULL
SQL;
        $params = array(
            'cinema_id' => $cinemaId
        );

        if ($hallNum) {
            $sql .= " AND ch.hall_num = :hall_num";
            $params['hall_num'] = $hallNum;
        }

        return $this->db->executeQuery($sql, $params)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @return Session
     */
    public function getSession($id)
    {
        return Session::getFactory()->find_one($id);
    }

    /**
     * @param int $sessionId
     * @return int[]
     */
    public function getPurchasedPlaces($sessionId)
    {
        return $this->db->executeQuery(
            "SELECT place FROM ticket WHERE session_id = :session_id",
            array('session_id' => $sessionId)
        )->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @param int $sessionId
     * @param int[] $places
     * @throws RE
     * @return string
     */
    public function createTickets($sessionId, $places)
    {
        try {
            $this->db->beginTransaction();
            $code = uniqid($sessionId . '-' . implode(':', $places) . '-', true);

            foreach ($places as $place) {
                $ticket = Ticket::create()
                    ->setCode($code)
                    ->setSessionId($sessionId)
                    ->setPlace($place);

                $ticket->save();
            }

            $this->db->commit();
            return $code;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw RE::error('PlaceAlreadyPurchased');
        }
    }

    /**
     * @param int $sessionId
     * @return int
     */
    public function getMaxPlacesForSession($sessionId)
    {
        $sql = <<<SQL
SELECT
    ch.places
FROM
    session s LEFT JOIN
    cinema_hall ch ON s.cinema_hall_id = ch.id
WHERE
    s.id = :session_id;
SQL;

        return (int) $this->db->executeQuery($sql, array('session_id' => $sessionId))->fetchColumn();
    }

    /**
     * @param string $code
     * @return Ticket[]|\IdiormResultSet
     */
    public function getTicketsByCode($code)
    {
        return Ticket::getFactory()->where('code', $code)->find_many();
    }

    /**
     * @param Ticket[] $tickets
     * @return int[]
     * @throws \Exception
     */
    public function deleteTickets($tickets)
    {
        try {
            $this->db->beginTransaction();

            $ids = array();
            foreach ($tickets as $ticket) {
                $ids[] = $ticket->getId();
                $ticket->delete();
            }

            $this->db->commit();
            return $ids;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}