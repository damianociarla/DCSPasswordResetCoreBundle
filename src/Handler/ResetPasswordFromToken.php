<?php

namespace DCS\PasswordReset\CoreBundle\Handler;

use DCS\PasswordReset\CoreBundle\Exception\ResetRequestNotFoundException;
use DCS\PasswordReset\CoreBundle\Repository\ResetRequestRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ResetPasswordFromToken
{
    /**
     * @var ResetRequestRepositoryInterface
     */
    private $resetRequestRepository;

    /**
     * @var ResetPassword
     */
    private $resetPasswordHandler;

    /**
     * ResetPassword constructor.
     *
     * @param ResetRequestRepositoryInterface $resetRequestRepository
     * @param ResetPassword $resetPasswordHandler
     */
    public function __construct(ResetRequestRepositoryInterface $resetRequestRepository, ResetPassword $resetPasswordHandler)
    {
        $this->resetRequestRepository = $resetRequestRepository;
        $this->resetPasswordHandler = $resetPasswordHandler;
    }

    /**
     * Performs the reset password
     *
     * @param string $token
     * @param string $password
     * @throws ResetRequestNotFoundException
     */
    public function __invoke($token, $password)
    {
        $resetRequest = $this->resetRequestRepository->findOneByToken($token);

        if (null === $resetRequest) {
            throw new ResetRequestNotFoundException($token);
        }

        call_user_func_array($this->resetPasswordHandler, [$resetRequest, $password]);
    }
}