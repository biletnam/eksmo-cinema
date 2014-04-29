<?php
namespace Eksmo\Cinema\Controller\Request;

use Eksmo\Cinema\App;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractApiRequest
{
    /**
     * @var Request
     */
    private $httpRequest;

    /**
     * @var App
     */
    protected $app;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @param Request $httpRequest
     * @param App $app
     */
    public function __construct(Request $httpRequest, App $app)
    {
        $this->httpRequest = $httpRequest;
        $this->app = $app;
    }

    public function validate()
    {
        $params = array_merge(
            $this->httpRequest->attributes->get('_route_params', array()),
            $this->httpRequest->query->all(),
            $this->httpRequest->request->all()
        );

        $this->validateStructure($params);
        $this->validateBusiness();
    }

    public function validateStructure($data)
    {
        $this->data = $data;
    }

    public function validateBusiness()
    {

    }

    /**
     * @param string $str
     * @return string
     */
    private static function fromCamelCase($str)
    {
        $str[0] = strtolower($str[0]);
        $func   = create_function('$c', 'return "_" . strtolower($c[1]);');

        return preg_replace_callback('/([A-Z])/', $func, $str);
    }

    public function __call($method, $arguments)
    {
        if (substr($method, 0, 3) != 'get') {
            throw new \BadMethodCallException($method);
        }

        $fieldName = static::fromCamelCase(substr($method, 3));
        if (isset($this->data[$fieldName])) {
            return $this->data[$fieldName];
        } else {
            return null;
        }
    }

    /**
     * @return string
     */
    public static function getClass()
    {
        return get_called_class();
    }
}