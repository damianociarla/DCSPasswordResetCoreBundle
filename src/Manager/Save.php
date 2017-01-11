<?php

namespace DCS\PasswordReset\CoreBundle\Manager;

use DCS\PasswordReset\CoreBundle\DCSPasswordResetCoreEvents;
use DCS\PasswordReset\CoreBundle\Event\ResetRequestEvent;
use DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Save
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * Save constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Save user
     *
     * @see DCSPasswordResetCoreEvents::BEFORE_SAVE_RESET_REQUEST
     * @see DCSPasswordResetCoreEvents::SAVE_RESET_REQUEST
     * @see DCSPasswordResetCoreEvents::AFTER_SAVE_RESET_REQUEST
     *
     * @param ResetRequestInterface $resetRequest
     */
    public function __invoke(ResetRequestInterface $resetRequest)
    {
        $event = new ResetRequestEvent($resetRequest);

        $this->dispatcher->dispatch(DCSPasswordResetCoreEvents::BEFORE_SAVE_RESET_REQUEST, $event);
        $this->dispatcher->dispatch(DCSPasswordResetCoreEvents::SAVE_RESET_REQUEST, $event);
        $this->dispatcher->dispatch(DCSPasswordResetCoreEvents::AFTER_SAVE_RESET_REQUEST, $event);
    }
}