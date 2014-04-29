<?php
namespace Eksmo\Cinema\Controller;

use Eksmo\Cinema\App;
use Eksmo\Cinema\Controller\Request\AbstractApiRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param App $app
     * @param Request $request
     */
    public function __construct(App $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
    }

    /**
     * @param string $class
     * @return AbstractApiRequest|Request
     */
    protected function getRequest($class = null)
    {
        if ($class) {
            /** @var AbstractApiRequest $request */
            $request = new $class($this->request, $this->app);
            $request->validate();

            return $request;
        } else {
            return $this->request;
        }
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    protected function json(array $data = array())
    {
        return new JsonResponse(array('data' => $data));
    }

    /**
     * @return string
     */
    public static function getClass()
    {
        return get_called_class();
    }
}