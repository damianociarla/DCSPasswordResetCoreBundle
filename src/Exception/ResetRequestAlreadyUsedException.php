<?php

namespace DCS\PasswordReset\CoreBundle\Exception;

use DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface;

class ResetRequestAlreadyUsedException extends \Exception
{
    /**
     * @var ResetRequestInterface
     */
    private $requestRequest;

    /**
     * ResetRequestAlreadyUsedException constructor.
     *
     * @param ResetRequestInterface $requestRequest
     */
    public function __construct(ResetRequestInterface $requestRequest)
    {
        parent::__construct(sprintf('The request with the token "%s" is already used', $requestRequest->getToken()));
        $this->requestRequest = $requestRequest;
    }

    /**
     * Get requestRequest
     *
     * @return ResetRequestInterface
     */
    public function getResetRequest()
    {
        return $this->requestRequest;
    }
}