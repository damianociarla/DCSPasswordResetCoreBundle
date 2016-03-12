<?php

namespace DCS\PasswordReset\CoreBundle\Event;

trait AuthorizableEvent
{
    /**
     * @var bool
     */
    private $authorized = true;

    /**
     * Is authorized create new request
     *
     * @return boolean
     */
    public function isAuthorized()
    {
        return $this->authorized;
    }

    /**
     * Sets as unauthorized to create a new ResetRequest
     */
    public function setAsUnauthorized()
    {
        $this->authorized = false;
    }

    /**
     * Sets as unauthorized to create a new ResetRequest
     */
    public function setAsAuthorized()
    {
        $this->authorized = true;
    }
}