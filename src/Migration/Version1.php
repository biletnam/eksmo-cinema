<?php
namespace Eksmo\Cinema\Migration;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version1 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $cinema = $schema->createTable('cinema');
        $cinema->addColumn('id', 'integer', array('auto_increment' => true));
        $cinema->addColumn('name', 'string');
        $cinema->setPrimaryKey(array('id'));

        $cinemaHall = $schema->createTable('cinema_hall');
        $cinemaHall->addColumn('id', 'integer', array('auto_increment' => true));
        $cinemaHall->addColumn('cinema_id', 'integer');
        $cinemaHall->addColumn('hall_num', 'integer');
        $cinemaHall->addColumn('places', 'integer');
        $cinemaHall->setPrimaryKey(array('id'));
        $cinemaHall->addUniqueIndex(array('cinema_id', 'hall_num'));

        $film = $schema->createTable('film');
        $film->addColumn('id', 'integer', array('auto_increment' => true));
        $film->addColumn('title', 'string');
        $film->setPrimaryKey(array('id'));

        $session = $schema->createTable('session');
        $session->addColumn('id', 'integer', array('auto_increment' => true));
        $session->addColumn('cinema_hall_id', 'integer');
        $session->addColumn('film_id', 'integer');
        $session->addColumn('start', 'datetime');
        $session->addColumn('stop', 'datetime');
        $session->setPrimaryKey(array('id'));

        $ticket = $schema->createTable('ticket');
        $ticket->addColumn('id', 'integer', array('auto_increment' => true));
        $ticket->addColumn('session_id', 'integer');
        $ticket->addColumn('place', 'integer');
        $ticket->addColumn('code', 'string');
        $ticket->setPrimaryKey(array('id'));
        $ticket->addUniqueIndex(array('session_id', 'place'));
    }

    public function down(Schema $schema)
    {
        $schema->dropTable('cinema');
        $schema->dropTable('cinema_hall');
        $schema->dropTable('film');
        $schema->dropTable('session');
        $schema->dropTable('ticket');
    }
}