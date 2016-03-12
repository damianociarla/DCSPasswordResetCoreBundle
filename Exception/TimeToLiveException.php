<?php

namespace DCS\PasswordReset\CoreBundle\Exception;

use DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface;

class TimeToLiveException extends \Exception
{
    /**
     * @var ResetRequestInterface
     */
    private $resetRequest;

    /**
     * TimeToLiveException constructor.
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