<?php

namespace DCS\PasswordReset\CoreBundle\Exception;

class ResetRequestNotFoundException extends \Exception
{
    /**
     * @var string
     */
    private $token;

    /**
     * ResetRequestNotFoundException constructor.
     *
     * @param string $token
     */
    public function __construct($token)
    {
        parent::__construct(sprintf('Reset request not found with the token: %s', $token));
        $this->token = $token;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}