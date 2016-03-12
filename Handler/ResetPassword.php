<?php

namespace DCS\PasswordReset\CoreBundle\Handler;

use DCS\User\CoreBundle\Helper\PasswordHelperInterface;
use DCS\User\CoreBundle\Manager\Save as UserSave;
use DCS\PasswordReset\CoreBundle\Checker\IsAvailableResetRequest;
use DCS\PasswordReset\CoreBundle\DCSPasswordResetCoreEvents;
use DCS\PasswordReset\CoreBundle\Event\ResetRequestCheckerEvent;
use DCS\PasswordReset\CoreBundle\Exception\ResetRequestAlreadyUsedException;
use DCS\PasswordReset\CoreBundle\Exception\TimeToLiveException;
use DCS\PasswordReset\CoreBundle\Exception\UnauthorizedResetPasswordException;
use DCS\PasswordReset\CoreBundle\Manager\Save as ResetRequestSave;
use DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface;
use DCS\PasswordReset\CoreBundle\Service\DateTimeGenerator\DateTimeGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ResetPassword
{
    /**
     * @var IsAvailableResetRequest
     */
    private $isAvailableResetRequestChecker;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var UserSave
     */
    private $userSave;

    /**
     * @var PasswordHelperInterface
     */
    private $passwordHelper;

    /**
     * @var ResetRequestSave
     */
    private $resetRequestSave;

    /**
     * @var DateTimeGeneratorInterface
     */
    private $dateTimeGenerator;

    /**
     * ResetPassword constructor.
     *
     * @param IsAvailableResetRequest $isAvailableResetRequestChecker
     * @param EventDispatcherInterface $eventDispatcher
     * @param UserSave $userSave
     * @param PasswordHelperInterface $passwordHelper
     * @param ResetRequestSave $resetRequestSave
     * @param DateTimeGeneratorInterface $dateTimeGenerator
     */
    public function __construct(
        IsAvailableResetRequest $isAvailableResetRequestChecker,
        EventDispatcherInterface $eventDispatcher,
        UserSave $userSave,
        PasswordHelperInterface $passwordHelper,
        ResetRequestSave $resetRequestSave,
        DateTimeGeneratorInterface $dateTimeGenerator
    ) {
        $this->isAvailableResetRequestChecker = $isAvailableResetRequestChecker;
        $this->eventDispatcher = $eventDispatcher;
        $this->userSave = $userSave;
        $this->passwordHelper = $passwordHelper;
        $this->resetRequestSave = $resetRequestSave;
        $this->dateTimeGenerator = $dateTimeGenerator;
    }

    public function __invoke(ResetRequestInterface $resetRequest, $password)
    {
        if (null !== $resetRequest->getUsedAt()) {
            throw new ResetRequestAlreadyUsedException($resetRequest);
        }

        if (!call_user_func($this->isAvailableResetRequestChecker, $resetRequest)) {
            throw new TimeToLiveException($resetRequest);
        }

        $event = new ResetRequestCheckerEvent($resetRequest);
        $this->eventDispatcher->dispatch(DCSPasswordResetCoreEvents::BEFORE_RESET_PASSWORD, $event);

        if (!$event->isAuthorized()) {
            throw new UnauthorizedResetPasswordException($resetRequest);
        }

        $user = $resetRequest->getUser();
        $this->passwordHelper->updateUserPassword($user, $password);
        call_user_func($this->userSave, $user);

        $resetRequest->setUsedAt($this->dateTimeGenerator->generate());
        call_user_func($this->resetRequestSave, $resetRequest);
    }
}