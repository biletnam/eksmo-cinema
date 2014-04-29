<?php
namespace Eksmo\Cinema\Migration;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version2 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $cinema = $schema->getTable('cinema');
        $cinemaHall = $schema->getTable('cinema_hall');
        $session = $schema->getTable('session');
        $film = $schema->getTable('film');
        $ticket = $schema->getTable('ticket');

        $cinemaHall->addForeignKeyConstraint($cinema, array('cinema_id'), array('id'));
        $session->addForeignKeyConstraint($cinemaHall, array('cinema_hall_id'), array('id'));
        $session->addForeignKeyConstraint($film, array('film_id'), array('id'));
        $ticket->addForeignKeyConstraint($session, array('session_id'), array('id'));
    }

    public function down(Schema $schema)
    {
    }
}