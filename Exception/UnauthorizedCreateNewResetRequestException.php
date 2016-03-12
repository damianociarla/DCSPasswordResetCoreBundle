<?php

namespace DCS\PasswordReset\CoreBundle\Exception;

use DCS\User\CoreBundle\Model\UserInterface;

class UnauthorizedCreateNewResetRequestException extends \Exception
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * UnauthorizedCreateNewResetRequestException constructor.
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