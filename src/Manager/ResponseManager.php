<?php
namespace Eksmo\Cinema\Manager;

use Eksmo\Cinema\Exception\RestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResponseManager
{
    /**
     * @var Request
     */
    private static $request;

    /**
     * @param Request $request
     */
    public static function setRequest(Request $request)
    {
        self::$request = $request;
    }

    /**
     * @return string
     */
    public static function getClass()
    {
        return get_called_class();
    }

    /**
     * @param \Exception $e
     * @param int $code
     * @return JsonResponse
     */
    public static function error(\Exception $e, $code)
    {
        $error = array(
            'code' => $code
        );

        if ($e instanceof RestException) {
            /** @var RestException $e */

            $error['code'] = $e->getHttpCode();
            $error['error_type'] = $e->getErrorType();
        } elseif ($e instanceof NotFoundHttpException) {
            /** @var NotFoundHttpException $e */
            $error['code'] = 404;
            $error['error_type'] = 'MethodNotFoundError';
        } else {
            if ($code == 500) {
                $error['error_type'] = 'ServerInternalError';
            } else {
                $error['error_type'] = 'ApiInternalError';
            }

            $error['error_message'] = $e->getMessage();
        }

        $request = self::$request;
        $params = array_merge(
            $request->attributes->get('_route_params', array()),
            $request->query->all(),
            $request->request->all()
        );

        $error['request'] = array(
            'path' => $request->getPathInfo(),
            'params' => $params
        );

        return new JsonResponse(array('error' => $error), $error['code']);
    }
}