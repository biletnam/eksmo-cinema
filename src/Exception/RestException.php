<?php
namespace Eksmo\Cinema\Exception;

use Symfony\Component\HttpFoundation\Response;

class RestException extends \Exception
{
    /**
     * @var string
     */
    private $errorType;

    /**
     * @var int
     */
    private $httpCode;

    /**
     * @param string $errorType
     * @param int $httpCode
     * @return RestException
     */
    public static function error($errorType, $httpCode = Response::HTTP_BAD_REQUEST)
    {
        return new self($errorType, $httpCode);
    }

    /**
     * @param string $errorType
     * @param int $httpCode
     */
    public function __construct($errorType, $httpCode = Response::HTTP_BAD_REQUEST)
    {
        $this->errorType = $errorType;
        $this->httpCode = $httpCode;

        parent::__construct(sprintf("Error type: '%s'. Http code: '%s'.", $errorType, $httpCode));
    }

    /**
     * @return string
     */
    public function getErrorType()
    {
        return $this->errorType;
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }
}