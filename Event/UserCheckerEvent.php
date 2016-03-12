<?php

namespace DCS\PasswordReset\CoreBundle\Event;

use DCS\User\CoreBundle\Model\UserInterface;
use Symfony\Component\EventDispatcher\Event;

class UserCheckerEvent extends Event
{
    use AuthorizableEvent;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * UserCheckerEvent constructor.
     *
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
}