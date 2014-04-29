<?php
namespace Eksmo\Cinema;

use Doctrine\DBAL\Connection;
use Eksmo\Cinema\Controller\ApiController;
use Eksmo\Cinema\Controller\ControllerResolver;
use Eksmo\Cinema\Manager\DbManager;
use Eksmo\Cinema\Manager\ResponseManager;
use Silex\Application;
use Silex\Controller;
use Symfony\Component\HttpFoundation\Request;

class App extends Application
{
    /**
     * @param string $env
     */
    public function __construct($env = 'dev')
    {
        date_default_timezone_set('Europe/Moscow');

        $params = require_once __DIR__ . '/config.php';
        $params['env'] = $env;
        $params['root_dir'] = __DIR__ . '/../../';

        parent::__construct($params);
        $this->regDb();

        $app = $this;
        $this['resolver'] = $this->share(function () use ($app) {
            return new ControllerResolver($app);
        });
    }

    private function regDb()
    {
        $this->register(new \Silex\Provider\DoctrineServiceProvider(), array(
            'db.options' => $this['db.options']
        ));

        /** @var Connection $db */
        $db = $this['db'];
        \ORM::set_db($db->getWrappedConnection());

        $this['db.manager'] = $this->share(function () use ($db) {
            return new DbManager($db);
        });
    }

    public function addRoutes()
    {
        $this->addApiRoute('get',  '/cinema/{cinema_id}/schedule', ApiController::getClass(), 'getCinemaSchedule');
        $this->addApiRoute('get',  '/film/{film_id}/schedule',     ApiController::getClass(), 'getFilmSchedule');
        $this->addApiRoute('get',  '/session/{session_id}/places', ApiController::getClass(), 'getSessionPlaces');
        $this->addApiRoute('post', '/tickets/buy',                 ApiController::getClass(), 'buyTickets');
        $this->addApiRoute('post', '/tickets/reject/{code}',       ApiController::getClass(), 'rejectTickets');
    }

    public function boot()
    {
        parent::boot();

        $this->addRoutes();
        $this->error(array(ResponseManager::getClass(), 'error'));
        $this->before(function (Request $request) {
            ResponseManager::setRequest($request);
        }, Application::EARLY_EVENT);
    }

    /**
     * @param string $method
     * @param string $route
     * @param string $controllerClass
     * @param string $controllerMethod
     * @return Controller
     */
    public function addApiRoute($method, $route, $controllerClass, $controllerMethod)
    {
        return $this->addRoute($method, '/api' . $route, $controllerClass, $controllerMethod);
    }

    /**
     * @param string $method
     * @param string $route
     * @param string $controllerClass
     * @param string $controllerMethod
     * @return Controller
     */
    public function addRoute($method, $route, $controllerClass, $controllerMethod)
    {
        $action = $controllerClass . '::' . $controllerMethod . 'Action';
        $controller = $this->$method($route, $action);

        return $controller;
    }

    /**
     * @return DbManager
     */
    public function getDbManager()
    {
        return $this['db.manager'];
    }
}