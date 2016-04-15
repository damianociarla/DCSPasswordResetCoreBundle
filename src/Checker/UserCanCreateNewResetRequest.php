<?php

namespace DCS\PasswordReset\CoreBundle\Checker;

use DCS\User\CoreBundle\Model\UserInterface;
use DCS\PasswordReset\CoreBundle\Repository\ResetRequestRepositoryInterface;

class UserCanCreateNewResetRequest
{
    /**
     * @var ResetRequestRepositoryInterface
     */
    private $resetRequestRepository;

    /**
     * @var TimeHasPassedSinceLastResetRequest
     */
    private $timeHasPassedSinceLastResetRequest;

    /**
     * DCSPasswordResetEventSubscriber constructor.
     *
     * @param ResetRequestRepositoryInterface $resetRequestRepository
     * @param TimeHasPassedSinceLastResetRequest $timeHasPassedSinceLastResetRequest
     */
    public function __construct(ResetRequestRepositoryInterface $resetRequestRepository, TimeHasPassedSinceLastResetRequest $timeHasPassedSinceLastResetRequest)
    {
        $this->resetRequestRepository = $resetRequestRepository;
        $this->timeHasPassedSinceLastResetRequest = $timeHasPassedSinceLastResetRequest;
    }

    /**
     * Check if user can create a new ResetRequest
     *
     * @param UserInterface $user
     * @return bool|mixed
     */
    public function __invoke(UserInterface $user)
    {
        $resetRequest = $this->resetRequestRepository->findLatestNotUsedByUser($user);

        if (null === $resetRequest) {
            return true;
        }

        return call_user_func($this->timeHasPassedSinceLastResetRequest, $resetRequest);
    }
}