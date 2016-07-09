<?php

namespace DCS\PasswordReset\CoreBundle\Handler;

use DCS\PasswordReset\CoreBundle\Checker\UserCanCreateNewResetRequest;
use DCS\PasswordReset\CoreBundle\DCSPasswordResetCoreEvents;
use DCS\PasswordReset\CoreBundle\Event\UserCheckerEvent;
use DCS\PasswordReset\CoreBundle\Exception\UnauthorizedCreateNewResetRequestException;
use DCS\PasswordReset\CoreBundle\Manager\Save;
use DCS\PasswordReset\CoreBundle\Service\ResetRequestFactoryInterface;
use DCS\User\CoreBundle\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CreateResetRequest
{
    /**
     * @var UserCanCreateNewResetRequest
     */
    private $userCanCreateNewResetRequestChecker;

    /**
     * @var ResetRequestFactoryInterface
     */
    private $resetRequestFactory;

    /**
     * @var Save
     */
    private $requestSave;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * CreateResetRequest constructor.
     *
     * @param UserCanCreateNewResetRequest $userCanCreateNewResetRequestChecker
     * @param ResetRequestFactoryInterface $resetRequestFactory
     * @param Save $requestSave
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        UserCanCreateNewResetRequest $userCanCreateNewResetRequestChecker,
        ResetRequestFactoryInterface $resetRequestFactory,
        Save $requestSave,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->userCanCreateNewResetRequestChecker = $userCanCreateNewResetRequestChecker;
        $this->resetRequestFactory = $resetRequestFactory;
        $this->requestSave = $requestSave;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Create and save a new ResetRequest
     *
     * @param UserInterface $user
     * @return \DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface
     *
     * @throws UnauthorizedCreateNewResetRequestException
     * @throws \Exception
     */
    public function __invoke(UserInterface $user)
    {
        if (!call_user_func($this->userCanCreateNewResetRequestChecker, $user)) {
            throw new UnauthorizedCreateNewResetRequestException($user);
        }

        $event = new UserCheckerEvent($user);
        $this->eventDispatcher->dispatch(DCSPasswordResetCoreEvents::BEFORE_CREATE_RESET_REQUEST, $event);

        if (!$event->isAuthorized()) {
            throw new UnauthorizedCreateNewResetRequestException($user);
        }

        $resetRequest = $this->resetRequestFactory->createFromUser($user);
        call_user_func($this->requestSave, $resetRequest);

        return $resetRequest;
    }
}