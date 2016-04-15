<?php

namespace DCS\PasswordReset\CoreBundle\Exception;

use DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface;

class UnauthorizedResetPasswordException extends \Exception
{
    /**
     * @var ResetRequestInterface
     */
    private $resetRequest;

    /**
     * UnauthorizedResetPasswordException constructor.
     *
     * @param ResetRequestInterface $resetRequest
     */
    public function __construct(ResetRequestInterface $resetRequest)
    {
        parent::__construct('Unauthorized reset password');
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