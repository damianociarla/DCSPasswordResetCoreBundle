<?php

namespace DCS\PasswordReset\CoreBundle\Event;

use DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface;
use Symfony\Component\EventDispatcher\Event;

class ResetRequestEvent extends Event
{
    /**
     * @var ResetRequestInterface
     */
    private $resetRequest;

    /**
     * ResetRequestEvent constructor.
     *
     * @param ResetRequestInterface $resetRequest
     */
    public function __construct(ResetRequestInterface $resetRequest)
    {
        $this->resetRequest = $resetRequest;
    }

    /**
     * Get resetRequest
     *
     * @return ResetRequestInterface
     */
    public function getResetRequest()
    {
        return $this->resetRequest;
    }
}