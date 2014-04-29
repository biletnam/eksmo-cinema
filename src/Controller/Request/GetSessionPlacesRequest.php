<?php
namespace Eksmo\Cinema\Controller\Request;

use Eksmo\Cinema\Exception\RestException as RE;

/**
 * @method int getSessionId
 */
class GetSessionPlacesRequest extends AbstractApiRequest
{
    public function validateBusiness()
    {
        $session = $this->app->getDbManager()->getSession($this->getSessionId());
        if (!$session) {
            throw RE::error('InvalidSession');
        }
    }
}