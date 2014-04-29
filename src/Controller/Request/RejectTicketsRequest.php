<?php
namespace Eksmo\Cinema\Controller\Request;

use Eksmo\Cinema\Model\Ticket;
use Eksmo\Cinema\Exception\RestException as RE;

/**
 * @method int getCode
 */
class RejectTicketsRequest extends AbstractApiRequest
{
    /**
     * @var Ticket[]
     */
    private $tickets;

    /**
     * @return Ticket[]
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    public function validateBusiness()
    {
        $dbManager = $this->app->getDbManager();
        if ($this->tickets = $dbManager->getTicketsByCode($this->getCode())) {
            /** @var Ticket $ticket */
            $ticket = reset($this->tickets);
            $session = $dbManager->getSession($ticket->getSessionId());
            $hourDiff =  $session->getStart()->diff(new \DateTime())->h;

            if ($hourDiff < 1) {
                throw RE::error('IllegalSessionStart');
            }
        } else {
            throw RE::error('InvalidCode');
        }
    }
}