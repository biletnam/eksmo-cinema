<?php
namespace Eksmo\Cinema\Command;

use Eksmo\Cinema\Model\Cinema;
use Eksmo\Cinema\Model\CinemaHall;
use Eksmo\Cinema\Model\Film;
use Eksmo\Cinema\Model\Session;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadFixturesCommand extends Command
{
    protected function configure()
    {
        $this->setName('fixtures:load');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $db = \ORM::get_db();
        $db->exec('DELETE FROM ticket');
        $db->exec('DELETE FROM session');
        $db->exec('DELETE FROM cinema_hall');
        $db->exec('DELETE FROM cinema');
        $db->exec('DELETE FROM film');

        $cinema1 = $this->createCinema('БольКино');
        $cinema2 = $this->createCinema('Диван Фильм');

        $hall1 = $this->createCinemaHall($cinema1, 1, 100);
        $hall2 = $this->createCinemaHall($cinema1, 2, 200);
        $hall3 = $this->createCinemaHall($cinema2, 1, 150);
        $hall4 = $this->createCinemaHall($cinema2, 2, 300);

        $film1 = $this->createFilm('Самый лучший фильм 2');
        $film2 = $this->createFilm('Зеленый слоник');
        $film3 = $this->createFilm('Сало, или 120 дней Содома');

        $this->createSessions($hall1, $film1, array(
            array('2014-06-01 09:00:00', '2014-05-01 10:30:00'),
            array('2014-06-01 11:00:00', '2014-05-01 12:30:00'),
            array('2014-06-01 13:00:00', '2014-05-01 14:30:00'),
        ));

        $this->createSessions($hall2, $film3, array(
            array('2014-05-02 09:00:00', '2014-05-01 10:30:00'),
            array('2014-05-03 11:00:00', '2014-05-01 12:30:00'),
            array('2014-05-04 13:00:00', '2014-05-01 14:30:00'),
        ));

        $this->createSessions($hall3, $film2, array(
            array('2014-05-09 09:00:00', '2014-05-01 10:30:00'),
            array('2014-05-09 11:00:00', '2014-05-01 12:30:00'),
            array('2014-05-09 13:00:00', '2014-05-01 14:30:00'),
        ));
    }

    /**
     * @param string $name
     * @return Cinema
     */
    private function createCinema($name)
    {
        $cinema = Cinema::create()->setName($name);
        $cinema->save();

        return $cinema;
    }

    /**
     * @param Cinema $cinema
     * @param int $hallNum
     * @param int $places
     * @return CinemaHall
     */
    private function createCinemaHall(Cinema $cinema, $hallNum, $places)
    {
        $hall = CinemaHall::create()
            ->setCinemaId($cinema->getId())
            ->setHallNum($hallNum)
            ->setPlacesCnt($places);
        $hall->save();

        return $hall;
    }

    /**
     * @param string $title
     * @return Film
     */
    private function createFilm($title)
    {
        $film = Film::create()->setTitle($title);
        $film->save();

        return $film;
    }

    /**
     * @param CinemaHall $cinemaHall
     * @param Film $film
     * @param array $times
     * @return Session[]
     */
    private function createSessions(CinemaHall $cinemaHall, Film $film, array $times)
    {
        $sessions = array();

        foreach ($times as $sessionTime) {
            $start = new \DateTime($sessionTime[0]);
            $stop = new \DateTime($sessionTime[1]);

            $session = Session::create()
                ->setCinemaHallId($cinemaHall->getId())
                ->setFilmId($film->getId())
                ->setStart($start)
                ->setStop($stop);

            $session->save();
            $sessions[] = $session;
        }

        return $sessions;
    }
}